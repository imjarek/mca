<?php

declare(strict_types=1);

namespace UI\Http\Rest\Controller\Partner;

use App\Domain\Partner\Application\Command\CreatePartnerCommand;
use App\Domain\User\Repository\UserRepository;
use Assert\Assertion;
use Ramsey\Uuid\Uuid;
use UI\Http\Rest\Controller\CommandController;
use UI\Http\Rest\Response\OpenApiResponse;
use Assert\AssertionFailedException;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

final class CreatePartnerController extends CommandController
{
    /**
     * @Route(
     *     "/partner",
     *     name="partner_create",
     *     methods={"POST"}
     * )
     *
     * @OA\Response(
     *     response=201,
     *     description="User created successfully"
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
    public function __invoke(Request $request, UserRepository $userRepository): OpenApiResponse
    {
        $inn = $request->get('inn');
        $phone = $request->get('phone');
        $bik = $request->get('bik');
        $bankAccount = $request->get('bankAccount');
        $bank = $request->get('bank');
        $kpp = $request->get('kpp');
        $regionCode = $request->get('regionCode');
        $legalAddress = $request->get('legalAddress');
        $actualAdress = $request->get('actualAddress');

        Assertion::allNotNull([
            $inn, $phone, $bik, $kpp, $bank, $bankAccount, $legalAddress, $actualAdress
        ], "Required parameters missing");

        $uuid = Uuid::uuid4();

        $user = $userRepository->oneByUuid($this->getUser()->uuid());

        $commandRequest = new CreatePartnerCommand(
            $uuid,
            $inn,
            $phone,
            $bik,
            $kpp,
            $bank,
            $bankAccount,
            $regionCode,
            $legalAddress,
            $actualAdress,
            $user
        );

        $this->handle($commandRequest);

        return OpenApiResponse::created("/partner/$uuid");
    }
}
