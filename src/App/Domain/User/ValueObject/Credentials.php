<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Credentials
{
    /**
     * @ORM\Column(name="email", type="email", unique=true)
     */
    private Email $email;

    /**
     * @ORM\Column(name="password", type="hashed_password")
     */
    private HashedPassword $hashedPassword;

    public function __construct(Email $email, HashedPassword $hashedPassword)
    {
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->hashedPassword;
    }

    public function setEmail(Email $email)
    {
        return $this->email = $email;
    }

    public function setPassword(HashedPassword $hashedPassword)
    {
        return $this->hashedPassword = $hashedPassword;
    }
}
