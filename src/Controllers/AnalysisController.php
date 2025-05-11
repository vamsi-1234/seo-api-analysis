<?php
namespace App\Controllers;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Services\SEOWebAnalyzer;
use App\Models\AnalysisResult;
use App\Helpers\ResponseHelper;
use App\Exceptions\ValidationException;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="SEO Analysis API",
 *         description="API for analyzing web content for SEO factors",
 *         @OA\Contact(
 *             email="support@seoapi.com"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://localhost:8080",
 *         description="Local development server"
 *     ),
 *     @OA\Tag(
 *         name="Analysis",
 *         description="SEO analysis operations"
 *     )
 * )
 */
class AnalysisController
{
 /**
     * @OA\Post(
     *     path="/analyze",
     *     tags={"Analysis"},
     *     summary="Analyze web content",
     *     description="Analyzes provided HTML content for SEO factors",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"content"},
     *                 @OA\Property(
     *                     property="content",
     *                     type="string",
     *                     example="<html><head><meta name='description' content='sample'></head><body><h1>Title</h1></body></html>"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful analysis",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="keyword_density",
     *                 type="object",
     *                 example={"sample": 0.33}
     *             ),
     *             @OA\Property(
     *                 property="readability_score",
     *                 type="number",
     *                 format="float",
     *                 example=78.45
     *             ),
     *             @OA\Property(
     *                 property="headlines",
     *                 type="object",
     *                 example={"h1": 1, "h2": 0, "h3": 0}
     *             ),
     *             @OA\Property(
     *                 property="meta_description",
     *                 type="object",
     *                 example={
     *                     "length": 18,
     *                     "is_valid": true,
     *                     "content": "sample"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Content is required")
     *         )
     *     )
     * )
     */
    protected $analyzer;
    protected $responseHelper;

    public function __construct(SEOWebAnalyzer $analyzer, ResponseHelper $responseHelper)
    {
        $this->analyzer = $analyzer;
        $this->responseHelper = $responseHelper;
    }

    public function analyze(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = $request->getParsedBody();
            $this->validateInput($data);
            
            $results = $this->analyzer->analyze($data['content']);
            $this->saveResults($results);

            return $this->responseHelper->jsonResponse($response, $results);
        } catch (ValidationException $e) {
            return $this->responseHelper->errorResponse($response, $e->getMessage(), 400);
        } catch (\Throwable $e) {
            error_log('Error: ' . $e->getMessage());
            return $this->responseHelper->errorResponse($response, 'Internal server error', 500);
        }
    }

    private function validateInput(array $data): void
    {
        if (trim($data['content'] ?? '') === '') {
    throw new ValidationException('Content is required');
    }

        if (strlen($data['content']) > 100000) {
            throw new ValidationException('Content exceeds maximum allowed size (100KB)');
        }

        if (!preg_match('/<[a-z][\s\S]*>/i', $data['content'])) {
            throw new ValidationException('Invalid HTML content');
        }
    }

    private function saveResults(array $results): void
    {
        AnalysisResult::create([
            'keyword_density' => json_encode($results['keyword_density']),
            'readability_score' => $results['readability_score'],
            'headlines' => json_encode($results['headlines']),
            'meta_description' => json_encode($results['meta_description'])
        ]);
    }
}