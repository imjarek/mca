<?php


namespace App\Domain\User\Entity;

use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Domain\User\ValueObject\Credentials;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\HashedPassword;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use App\Domain\User\Event\UserEmailChanged;
use App\Domain\User\Event\UserSignedIn;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Shared\Infrastructure\AggregateRoot;
use App\Shared\Infrastructure\Bus\AsyncEvent\MessengerAsyncEventBus;
use App\Shared\Infrastructure\Event\Publisher\AsyncEventPublisher;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Doctrine\ORM\Mapping as ORM;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @ORM\Entity()
 * @ORM\Table(name="`user`")
 */
class User extends AggregateRoot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
    */
    private $uuid;

    /**
     *
     * @ORM\Embedded(class="App\Domain\User\ValueObject\Credentials", columnPrefix=false)
     */
    private Credentials $credentials;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $emailVerifiedAt;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTime $updatedAt;

    /**
     * @throws DateTimeException
     */
    public static function create(
        $uuid,
        $credentials,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): self {
        $uniqueEmailSpecification->isUnique($credentials->getEmail());
        $user = new self();
        $user->apply(new UserWasCreated($uuid, $credentials, DateTime::now()));

        return $user;
    }

    public function getFirstName():?string
    {
        return $this->firstname;
    }

    public function getLastName():?string
    {
        return $this->lastname;
    }

    /**
     * @throws DateTimeException
     */
    public function changeEmail(
        Email $email,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): void {
        $uniqueEmailSpecification->isUnique($email);
        $this->apply(new UserEmailChanged($this->uuid, $email, DateTime::now()));
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function signIn(string $plainPassword): void
    {
        if (!$this->credentials->getPassword()->match($plainPassword)) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        $this->apply(new UserSignedIn($this->uuid, $this->credentials->getEmail()));
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->uuid = $event->uuid;
        $this->setCredentials($event->credentials->getEmail(), $event->credentials->getPassword());
        $this->setCreatedAt($event->createdAt);
    }


    /**
     * @throws AssertionFailedException
     */
    protected function applyUserEmailChanged(UserEmailChanged $event): void
    {
        Assertion::notEq($this->email->toString(), $event->email->toString(), 'New email should be different');

        $this->setEmail($event->email);
        $this->setUpdatedAt($event->updatedAt);
    }

    protected function applyUserSignedIn($event)
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->dispatch($event);
    }

    private function setCredentials(Email $email, HashedPassword $password)
    {
        $this->credentials = new Credentials($email, $password);
    }
    private function setEmail(Email $email): void
    {
        $this->credentials->setEmail($email);
    }

    private function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->credentials->setPassword($hashedPassword);
    }

    private function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    private function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function createdAt(): string
    {
        return $this->createdAt->toString();
    }

    public function updatedAt(): ?string
    {
        return isset($this->updatedAt) ? $this->updatedAt->toString() : null;
    }

    public function email(): string
    {
        return $this->credentials->getEmail()->toString();
    }

    public function setEmailVerified()
    {
        $this->emailVerifiedAt = DateTime::now();
    }

    public function getEmailVerified()
    {
        return $this->emailVerifiedAt;
    }
    public function uuid(): string
    {
        return $this->uuid->toString();
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }
}
