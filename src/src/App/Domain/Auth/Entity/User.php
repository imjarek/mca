<?php


namespace App\Domain\User\Entity\User;

use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use App\Domain\Auth\Event\UserEmailChanged;
use App\Domain\Auth\Event\UserSignedIn;
use App\Domain\Auth\Event\UserWasCreated;
use App\Domain\Auth\Exception\InvalidCredentialsException;
use App\Domain\Auth\ValueObject\Auth\HashedPassword;
use App\Domain\Auth\ValueObject\Email;
use Assert\Assertion;
use Assert\AssertionFailedException;

use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User extends EventSourcedAggregateRoot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    private Email $email;

    private HashedPassword $hashedPassword;

    private ?DateTime $createdAt;

    private ?DateTime $updatedAt;

    /**
     * @throws DateTimeException
     */
    public static function create(
        UuidInterface $uuid,
        $username,
        $password
    ): self {
        $user = new self();

        $user->apply(new UserWasCreated($uuid, $credentials, DateTime::now()));

        return $user;
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
        if (!$this->hashedPassword->match($plainPassword)) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        $this->apply(new UserSignedIn($this->uuid, $this->email));
    }

    protected function applyUserWasCreated(UserWasCreated $event): void
    {
        $this->uuid = $event->uuid;

        $this->setEmail($event->credentials->email);
        $this->setHashedPassword($event->credentials->password);
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

    private function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    private function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
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
        return $this->email->toString();
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
