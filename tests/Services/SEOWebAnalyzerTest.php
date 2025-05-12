<?php
namespace App\Tests\Services;

use App\Services\SEOWebAnalyzer;
use PHPUnit\Framework\TestCase;

class SEOWebAnalyzerTest extends TestCase
{
    private $analyzer;

    protected function setUp(): void
    {
        $this->analyzer = new SEOWebAnalyzer();
    }

    public function testKeywordDensityAnalysis()
    {
        $html = '<html><body><p>test test example</p></body></html>';
        $result = $this->analyzer->analyze($html);
        
        $this->assertArrayHasKey('keyword_density', $result);
        $this->assertEquals(66.67, $result['keyword_density']['test']);
        $this->assertEquals(33.33, $result['keyword_density']['example']);
    }

    public function testReadabilityScore()
    {
        $html = '
        <html>
            <body>
                <p>This is a simple sentence. Here is another one. 
                This third sentence makes it more complex.</p>
            </body>
        </html>';

        $result = $this->analyzer->analyze($html);
        
        // Validate calculation structure
        $this->assertIsFloat($result['readability_score']);
        
        // Expected range based on actual content
        $this->assertEqualsWithDelta(48.08, $result['readability_score'], 5);
    }

    public function testHeadlineStructure()
    {
        $html = '<h1>Title</h1><h2>Sub1</h2><h2>Sub2</h2>';
        $result = $this->analyzer->analyze($html);
        
        $this->assertEquals(1, $result['headlines']['h1']);
        $this->assertEquals(2, $result['headlines']['h2']);
        $this->assertEquals(0, $result['headlines']['h3']);
    }

    public function testMetaDescriptionAnalysis()
    {
        $validDesc = str_repeat('a', 150);
        $html = "<meta name='description' content='$validDesc'>";
        
        $result = $this->analyzer->analyze($html);
        $this->assertTrue($result['meta_description']['is_valid']);
        
        $shortDesc = str_repeat('a', 50);
        $html = "<meta name='description' content='$shortDesc'>";
        $result = $this->analyzer->analyze($html);
        $this->assertFalse($result['meta_description']['is_valid']);
    }
}