<?php

namespace UI\Http\Rest\Controller\Auth;

use App\Domain\User\Exception\UnauthenticatedException;
use App\Domain\User\Infrastructure\ReadModel\UserView;
use App\Domain\User\Repository\UserRepository;
use App\Shared\Application\Query\Item;
use Assert\AssertionFailedException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Core\Security;
use Nelmio\ApiDocBundle\Annotation\Security as ApiSecurity;
use UI\Http\Rest\Controller\QueryController;

class UserInfoController extends QueryController
{
    protected Security $security;
    protected UserRepository $repository;

    public function __construct(UserRepository $repository, Security $security)
    {
        $this->security = $security;
        $this->repository = $repository;
    }
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
    public function userInfo()
    {
        $token = $this->security->getToken();

        if ($token instanceof JWTUserToken) {
            if ($authModel = $token->getUser()) {
                $user = $this->repository->oneByUuid($authModel->uuid());
            }
                $userData = [
                    'id' => $user->uuid(),
                    'email' => $user->email(),
                    'first_name' => $user->getFirstName(),
                    'last_name' => $user->getLastName(),
                    'email_verified' => $user->getEmailVerified()
                ];
                return $this->json(Item::fromPayload($user->uuid(), UserView::TYPE, $userData));
        }

        throw new UnauthenticatedException();
    }
}
