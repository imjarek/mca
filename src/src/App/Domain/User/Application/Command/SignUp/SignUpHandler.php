<?php

declare(strict_types=1);

namespace App\Domain\User\Application\Command\SignUp;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DateTimeException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\Entity\User;

final class SignUpHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->userRepository = $userRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    /**
     * @throws DateTimeException
     */
    public function __invoke(SignUpCommand $command): void
    {
        $user = User::create($command->uuid, $command->credentials, $this->uniqueEmailSpecification);

        $this->userRepository->store($user);
    }
}
