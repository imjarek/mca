<?php

namespace UI\Http\Rest\Controller\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Exception\EmailVerificationException;
use App\Domain\User\Repository\UserRepository;
use Assert\AssertionFailedException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use UI\Http\Web\Controller\DefaultController;
use OpenApi\Annotations as OA;
use Throwable;

class EmailVerificationController
{
    public function __construct(
        UserRepository $repository,
        VerifyEmailHelperInterface $verificator,
        MailerInterface $mailer
    )
    {
        $this->verificator = $verificator;
        $this->mailer = $mailer;
        $this->repository = $repository;
    }

    /**
     * @Route(
     *     "/verify_email",
     *     name="user email verification",
     *     methods={"GET"}
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\Parameter (
     *     name="email",
     *     in="path",
     *     @OA\Schema(type="string")
     * )
     * * @OA\Parameter (
     *     name="signature",
     *     in="path",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter (
     *     name="expires",
     *     in="path",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="User")
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->repository->oneByUuid($this->getUser()->uuid());

        try {
            $this->verificator->validateEmailConfirmation($request->getUri(), $user->uuid(), $user->email());

            $user->setEmailVerified();
            $this->repository->apply();

            return new JsonResponse(['message' => 'Email Verified Successfully']);
        } catch (\Exception $e) {
            throw new EmailVerificationException();
        }
    }
}