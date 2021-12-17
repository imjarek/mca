<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\Health;

use App\Shared\Infrastructure\Event\ReadModel\ElasticSearchEventRepository;
use UI\Http\Rest\Response\OpenApiResponse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class HealthController
{
    private ElasticSearchEventRepository $elasticSearchEventRepository;

    private MysqlReadModelUserRepository $mysqlReadModelUserRepository;

//    public function __construct(
//        ElasticSearchEventRepository $elasticSearchEventRepository,
//        MysqlReadModelUserRepository $mysqlReadModelUserRepository)
//    {
//        $this->elasticSearchEventRepository = $elasticSearchEventRepository;
//        $this->mysqlReadModelUserRepository = $mysqlReadModelUserRepository;
//    }

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
        $elastic = null;
        $mysql = null;

        if (
            true === $elastic = $this->elasticSearchEventRepository->isHealthly() &&
            true === $mysql = $this->mysqlReadModelUserRepository->isHealthy()
        ) {
            return OpenApiResponse::empty(200);
        }

        return OpenApiResponse::fromPayload(
            [
                'Healthy services' => [
                    'Elastic' => $elastic,
                    'MySQL' => $mysql,
                ],
            ],
            500
        );
    }
}
