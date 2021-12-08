<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\Order;

use App\Order\Application\Query\Order\FindById\FindByIdQuery;
use App\Shared\Application\Query\Item;
use UI\Http\Rest\Controller\QueryController;
use UI\Http\Rest\Response\OpenApi;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class GetOrderByIdController extends QueryController
{
    /**
     * @Route(
     *     "/order/{id}",
     *     name="find_order",
     *     methods={"GET"}
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns an order with a specific Id ",
     *     ref="#/components/responses/orders"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="email", type="string"),
     *     )
     * )
     *
     * @OA\Tag(name="Order")
     *
     * @Security(name="Bearer")
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function __invoke(string $id): OpenApi
    {
        Assertion::uuid($id, "Id not specified");

        $command = new FindByIdQuery($id);

        /** @var Item $user */
        $user = $this->ask($command);

        return $this->json($user);
    }
}
