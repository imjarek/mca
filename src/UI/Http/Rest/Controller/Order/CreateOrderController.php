<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\Order;

use App\Order\Application\Command\Create\OrderCreateCommand;
use App\Order\Application\Command\Create\OrderCreateHandler;
use UI\Http\Rest\Controller\CommandController;
use UI\Http\Rest\Response\OpenApiResponse;
use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class CreateOrderController extends CommandController
{
    /**
     * @Route(
     *     "/order",
     *     name="order_create",
     *     methods={"POST"}
     * )
     *
     * @OA\Response(
     *     response=201,
     *     description="Order created successfully"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\Response(
     *     response=409,
     *     description="Conflict"
     * )
     * @OA\RequestBody(
     *     @OA\Schema(type="object"),
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="description", type="string"),
     *     )
     * )
     *
     * @OA\Tag(name="Order")
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function __invoke(Request $request): OpenApi
    {
        $comment = $request->get('description');

        Assertion::notNull($comment, "Please write order description");
        $commandRequest = new OrderCreateCommand($comment);

        $this->handle($commandRequest);

        return OpenApi::created("/user/...");
    }
}
