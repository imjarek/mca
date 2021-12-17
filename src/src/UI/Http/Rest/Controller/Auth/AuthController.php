<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\Auth;

use App\Domain\User\Application\Command\SignIn\SignInCommand;
use App\Domain\User\Application\Query\Auth\GetToken\GetTokenQuery;
use App\Domain\User\Exception\InvalidCredentialsException;
use Nelmio\ApiDocBundle\Annotation\Security;
use UI\Http\Rest\Controller\CommandQueryController;
use UI\Http\Rest\Response\OpenApiResponse;
use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class AuthController extends CommandQueryController
{
    /**
     * @Route(
     *     "/auth",
     *     name="api_auth",
     *     methods={"POST"},
     *     requirements={
     *      "username": "\w+",
     *      "password": "\w+"
     *     }
     * )
     * @OA\Response(
     *     response=200,
     *     description="Login success",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(
     *          property="token", type="string"
     *        )
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\Response(
     *     response=401,
     *     description="Bad credentials"
     * )
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="password", type="string"),
     *         @OA\Property(property="username", type="string")
     *     )
     * )
     *
     * @OA\Tag(name="Auth")
     *
     * @throws AssertionFailedException
     * @throws InvalidCredentialsException
     * @throws Throwable
     */
    public function __invoke(Request $request): OpenApiResponse
    {
        $username = $request->get('username');

        Assertion::notNull($username, 'Username cant\'t be empty');

        $signInCommand = new SignInCommand(
            $username,
            $request->get('password')
        );

        $this->handle($signInCommand);

        return OpenApiResponse::fromPayload(
            [
                'token' => $this->ask(new GetTokenQuery($username)),
            ],
            OpenApiResponse::HTTP_OK
        );
    }
}
