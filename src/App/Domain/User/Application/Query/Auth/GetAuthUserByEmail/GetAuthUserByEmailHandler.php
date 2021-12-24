<?php

declare(strict_types=1);

namespace App\Domain\User\Application\Query\Auth\GetAuthUserByEmail;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\Domain\User\Repository\GetUserCredentialsByEmailInterface;
use App\Domain\User\Infrastructure\Auth;

final class GetAuthUserByEmailHandler implements QueryHandlerInterface
{
    private GetUserCredentialsByEmailInterface $userCredentialsByEmail;

    public function __construct(
        GetUserCredentialsByEmailInterface $userCredentialsByEmail
    ) {
        $this->userCredentialsByEmail = $userCredentialsByEmail;
    }

    public function __invoke(GetAuthUserByEmailQuery $query): Auth
    {
        [$uuid, $email, $hashedPassword] = $this->userCredentialsByEmail->getCredentialsByEmail($query->email);

        return Auth::create($uuid, $email, $hashedPassword);
    }
}
