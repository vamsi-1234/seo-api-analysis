<?php
namespace App\Helpers;

use Psr\Http\Message\ResponseInterface;

class ResponseHelper
{
    public function jsonResponse(ResponseInterface $response, $data): ResponseInterface
    {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function errorResponse(ResponseInterface $response, string $message, int $statusCode): ResponseInterface
    {
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}