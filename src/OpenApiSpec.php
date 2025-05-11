<?php
namespace App;

use OpenApi\Annotations as OA;

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
class OpenApiSpec {}