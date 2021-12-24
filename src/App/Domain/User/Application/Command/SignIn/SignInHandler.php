<?php

declare(strict_types=1);

namespace App\Domain\User\Application\Command\SignIn;

use App\Core\Shared\Application\Messaging\EventBusInterface;
use App\Domain\User\Event\UserSignedIn;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\Repository\CheckUserByEmailInterface;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\ValueObject\Email;
use App\Shared\Infrastructure\DomainMessage;
use Ramsey\Uuid\UuidInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class SignInHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userStore;

    private CheckUserByEmailInterface $userCollection;

    public function __construct(UserRepositoryInterface $userStore, CheckUserByEmailInterface $userCollection, $eventBus)
    {
        $this->userStore = $userStore;
        $this->userCollection = $userCollection;
        $this->eventBus = $eventBus;
    }

    public function __invoke(SignInCommand $command): void
    {
        $uuid = $this->uuidFromEmail($command->email);

        $user = $this->userStore->get($uuid);

        $user->signIn($command->plainPassword);

        $this->userStore->store($user);

        $this->eventBus->dispatch(new DomainMessage($user));
    }

    private function uuidFromEmail(Email $email): UuidInterface
    {
        $uuid = $this->userCollection->existsEmail($email);

        if (null === $uuid) {
            throw new InvalidCredentialsException();
        }

        return $uuid;
    }
}