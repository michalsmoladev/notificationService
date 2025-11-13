<?php

namespace App\Presentation\Controller;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag('Health')]
class HealthController extends AbstractController
{
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: []
    )]
    #[Route(path: '/api/health', name: 'app_api_health_check', methods: ['GET'])]
    public function healthCheckAction(): JsonResponse
    {
        return new JsonResponse();
    }
}
