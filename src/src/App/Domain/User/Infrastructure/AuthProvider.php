<?php

declare(strict_types=1);

namespace App\Domain\User\Infrastructure;

use App\Domain\User\ValueObject\Email;
use App\Shared\Infrastructure\Persistence\ReadModel\Exception\NotFoundException;
use App\Domain\User\Repository\UserRepository;
use Assert\AssertionFailedException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class AuthProvider implements UserProviderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        try {
            [$uuid, $email, $hashedPassword] = $this->userRepository->getCredentialsByEmail(
                Email::fromString($identifier)
            );

            return Auth::create($uuid, $email, $hashedPassword);
        } catch (NotFoundException) {
            throw new UserNotFoundException();
        }

    }
    /**
     * @throws NotFoundException
     * @throws AssertionFailedException
     * @throws NonUniqueResultException
     * @throws \Throwable
     *
     * @return Auth|UserInterface
     */
    public function loadUserByUsername(string $email)
    {
        [$uuid, $email, $hashedPassword] = $this->userRepository->getCredentialsByEmail(
            Email::fromString($email)
        );

        return Auth::create($uuid, $email, $hashedPassword);
    }

    /**
     * @throws NotFoundException
     * @throws AssertionFailedException
     * @throws NonUniqueResultException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByUsername($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return Auth::class === $class;
    }
}
