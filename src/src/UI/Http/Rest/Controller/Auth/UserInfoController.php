<?php

namespace UI\Http\Rest\Controller\Auth;

use App\Core\Exception\ApiException;
use App\Domain\User\Exception\UnauthenticatedException;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Security as ApiSecurity;
use UI\Http\Session;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use UI\Http\Rest\Response\OpenApi;

class UserInfoController
{
    /**
     * @Route(
     *     "/user_info",
     *     name="user_into",
     *     methods={"GET"}
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns authenticated user information (bearer token verification)",
     *     ref="#/components/responses/users"
     * )
     * @OA\Response(
     *     response=401,
     *     description="Not authenticated"
     * )
     *
     * @OA\Tag(name="Auth")
     *
     * @ApiSecurity(name="Bearer")
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function userInfo(Session $session)
    {
        if (!$session->get()->uuid()) {
            throw new ForbiddenException();
        }

        throw new UnauthenticatedException();
    }
}