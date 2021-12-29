<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller;

use App\Shared\Application\Query\Collection;
use App\Shared\Application\Query\Item;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Application\Query\QueryInterface;
use UI\Http\Rest\Response\OpenApiResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Throwable;

abstract class QueryController
{
    private const CACHE_MAX_AGE = 31536000; // Year.

    private QueryBusInterface $queryBus;

    private UrlGeneratorInterface $router;

    public function __construct(QueryBusInterface $queryBus, UrlGeneratorInterface $router)
    {
        $this->queryBus = $queryBus;
        $this->router = $router;
    }

    /**
     * @return Item|Collection|string|null
     *
     * @throws Throwable
     */
    protected function ask(QueryInterface $query)
    {
        return $this->queryBus->ask($query);
    }

    protected function jsonCollection(
        Collection $collection,
        int $status = OpenApiResponse::HTTP_OK,
        bool $isImmutable = false
    ): OpenApi {
        $response = OpenApiResponse::collection($collection, $status);

        $this->decorateWithCache($response, $collection, $isImmutable);

        return $response;
    }

    protected function json(Item $resource, int $status = OpenApiResponse::HTTP_OK): OpenApiResponse
    {
        return OpenApiResponse::one($resource, $status);
    }

    protected function route(string $name, array $params = []): string
    {
        return $this->router->generate($name, $params);
    }

    private function decorateWithCache(OpenApi $response, Collection $collection, bool $isImmutable): void
    {
        if ($isImmutable && $collection->limit === \count($collection->data)) {
            $response
                ->setMaxAge(self::CACHE_MAX_AGE)
                ->setSharedMaxAge(self::CACHE_MAX_AGE);
        }
    }
}
