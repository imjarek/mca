<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\Partner;

use App\Domain\User\Application\Command\SignUp\SignUpCommand;
use Ramsey\Uuid\Uuid;
use UI\Http\Rest\Controller\CommandController;
use UI\Http\Rest\Response\OpenApiResponse;
use Assert\Assertion;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class GetPartnerController extends CommandController
{
    /**
     * @Route(
     *     "/partner/{id}",
     *     name="get_partner",
     *     methods={"GET"}
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Successfully"
     * )
     * @OA\Response(
     *     response=404,
     *     description="Bad request"
     * )
     * @OA\RequestBody(
     *     @OA\Schema(type="object"),
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="bik", type="string"),
     *         @OA\Property(property="inn", type="string"),
     *         @OA\Property(property="kpp", type="string"),
     *         @OA\Property(property="bank", type="string"),
     *         @OA\Property(property="bank_account", type="string"),
     *         @OA\Property(property="legal_address", type="string"),
     *         @OA\Property(property="actual_address", type="string"),
     *         @OA\Property(property="phone", type="string")
     *     )
     * )
     *
     * @OA\Tag(name="Partner")
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function __invoke(Request $request): OpenApi
    {
        $email = $request->get('email');
        $plainPassword = $request->get('password');

        Assertion::notNull($email, "Email can\'t be null");
        Assertion::notNull($plainPassword, "Password can\'t be null");

        $uuid = Uuid::uuid4();

        $commandRequest = new SignUpCommand($uuid, $email, $plainPassword);

        $this->handle($commandRequest);

        return OpenApi::created("/user/$email");
    }
}
