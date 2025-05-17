<?php
namespace App\Services;

use DOMDocument;

class SEOWebAnalyzer
{
    public function analyze(string $html): array
    {
        if (empty(trim($html))) {
        throw new \InvalidArgumentException('Content cannot be empty');
        }
        return [
            'keyword_density' => $this->analyzeKeywordDensity($html),
            'readability_score' => $this->analyzeReadability($html),
            'headlines' => $this->analyzeHeadlines($html),
            'meta_description' => $this->analyzeMetaDescription($html)
        ];
    }

    private function analyzeKeywordDensity(string $html): array
    {
        $text = $this->extractText($html);
        $words = str_word_count(strtolower($text), 1);
        $total = count($words);
        
        $counts = array_count_values($words);
        arsort($counts);
        
        $density = [];
        foreach (array_slice($counts, 0, 10) as $word => $count) {
            $density[$word] = round(($count / $total) * 100, 2);
        }
        
        return $density;
    }

    private function analyzeHeadlines(string $html): array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        return [
            'h1' => $dom->getElementsByTagName('h1')->length,
            'h2' => $dom->getElementsByTagName('h2')->length,
            'h3' => $dom->getElementsByTagName('h3')->length
        ];
    }

    private function analyzeMetaDescription(string $html): array
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $metas = $dom->getElementsByTagName('meta');
        
        $description = '';
        foreach ($metas as $meta) {
            if ($meta->getAttribute('name') === 'description') {
                $description = $meta->getAttribute('content');
                break;
            }
        }
        
        $length = strlen($description);
        return [
            'length' => $length,
            'is_valid' => $length >= 70 && $length <= 160,
            'content' => $description
        ];
    }
    private function extractText(string $html): string
    {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $body = $dom->getElementsByTagName('body')->item(0);
        return $body ? trim(preg_replace('/\s+/', ' ', $body->textContent)) : '';
    }

    private function countWordSyllables(string $word): int
    {
        $word = preg_replace('/[^a-z]/i', '', strtolower($word));
        if (empty($word)) return 0;

        // Count vowel groups (e.g., "ea" = 1 group)
        preg_match_all('/[aeiouy]+/i', $word, $matches);
        $vowelGroups = count($matches[0]);

        // Subtract silent 'e' at the end
        $silentE = preg_match('/e$/i', $word) && ($vowelGroups > 1);
        $syllables = max(1, $vowelGroups - $silentE);

        // Handle 'le' endings (e.g., "table" → 2 syllables)
        if (preg_match('/[^aeiouy]le$/i', $word)) $syllables++;

        // Handle compound words (e.g., "mother-in-law")
        $syllables += substr_count($word, '-');

        return $syllables;
    }


    private function analyzeReadability(string $html): float
    {
        $text = $this->extractText($html);
        
        // Split sentences while preserving punctuation for splitting
        $sentences = preg_split('/(?<=[.!?])\s+(?=[A-Z])/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $words = str_word_count(strip_tags($text), 0); // Use actual word count
        
        if (count($sentences) === 0 || $words === 0) return 0.0;
        
        $syllables = $this->countSyllables($text);
        
        // Flesch-Kincaid formula
        return round(206.835 - 1.015 * ($words / count($sentences)) - 84.6 * ($syllables / $words), 2);
    }

    private function countSyllables(string $text): int
    {
        $text = preg_replace('/[^a-z ]/i', '', strtolower($text)); // Preserve spaces
        $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        
        $count = 0;
        foreach ($words as $word) {
            $count += $this->countWordSyllables($word);
        }
        return $count;
    }

    
}