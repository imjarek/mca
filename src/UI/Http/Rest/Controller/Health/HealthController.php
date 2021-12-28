<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\Health;

use App\Domain\User\Repository\UserRepository;
use App\Shared\Infrastructure\Event\ReadModel\ElasticSearchEventRepository;
use UI\Http\Rest\Response\OpenApiResponse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class HealthController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route(
     *     "/health",
     *     name="health",
     *     methods={"GET"}
     * )
     * @OA\Response(
     *     response=200,
     *     description="OK"
     * )
     * @OA\Response(
     *     response=500,
     *     description="Something not ok"
     * )
     *
     * @OA\Tag(name="Health")
     */
    public function __invoke(Request $request): OpenApiResponse
    {
        if (
            true === $db = $this->userRepository->isHealthy()
        ) {
            return OpenApiResponse::empty(200);
        }

        return OpenApiResponse::fromPayload(
            [
                'services' => [
                    'PostgreSQL' => $db,
                ],
            ],
            500
        );
    }
}
