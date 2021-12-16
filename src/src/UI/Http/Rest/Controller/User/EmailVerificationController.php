<?php

namespace UI\Http\Rest\Controller\User;

use App\Domain\Common\ApiException\AddEmployeeToShiftException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use UI\Http\Web\Controller\DefaultController;

class EmailVerificationController extends DefaultController
{
    public function __construct(VerifyEmailHelperInterface $verificator, MailerInterface $mailer)
    {
        $this->verificator = $verificator;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/verify_email", name="verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // TODO: proper exception
        $this->verificator->validateEmailConfirmation($request->getUri(), $user->uuid(), $user->email());

        // Mark your user as verified. e.g. switch a User::verified property to true

        $this->addFlash('success', 'Your e-mail address has been verified.');

        return $this->redirectToRoute('app_home');
    }
}