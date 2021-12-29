<?php

namespace App\Domain\Partner\Entity;

use App\Domain\Partner\Event\PartnerWasCreated;
use App\Domain\Partner\Specification\UniqueInnSpecificationInterface;
use App\Domain\Partner\ValueObject\Inn;
use App\Domain\User\Event\UserWasCreated;
use App\Domain\User\Specification\UniqueEmailSpecificationInterface;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use App\Shared\Infrastructure\AggregateRoot;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="partner")
 */
class Partner extends AggregateRoot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
    */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=14)
    */
    private $inn;

    /**
     * @ORM\Column(type="string", length=10)
    */
    private $bik;

    /**
     * @ORM\Column(type="string", length=20)
    */
    private $bankAccount;

    /**
     * @ORM\Column(type="string", length=200)
    */
    private $bank;

    /**
     * @ORM\Column(type="string", length=10)
    */
    private $kpp;

    /**
     * @ORM\Column(type="string", length=200)
    */
    private $legalAddress;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $actualAddress;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $phone;

    /**
     * @ORM\Column(type="string")
     */
    private $regionCode;

//  TODO: introduce partner region
//     * @ORM\ManyToOne(targetEntity="App\Shared\Domain\Entity\Region", inversedBy="region")
//     */
//    private $region;

    /**
     * @ORM\ManyToMany(targetEntity="App\Domain\User\Entity\User")
     * @ORM\JoinTable(name="user_partner",
     *      joinColumns={@ORM\JoinColumn(name="partner_id", referencedColumnName="uuid")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="uuid", unique=true)}
     *      )
     */
    private $users;

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
        $inn,
        $phone,
        $bik,
        $kpp,
        $bank,
        $bankAccount,
        $regionCode,
        $legalAddress,
        $actualAdress,
        UniqueInnSpecificationInterface $uniqueInnSpecification
    ): self {
        $uniqueInnSpecification->isUnique($inn);
        $partner = new self();
        $partner->apply(
            new PartnerWasCreated(
                $uuid,
                $inn,
                DateTime::now(),
                $phone,
                $bik,
                $kpp,
                $bank,
                $bankAccount,
                $regionCode,
                $legalAddress,
                $actualAdress
            )
        );
        return $partner;
    }

    protected function applyPartnerWasCreated(PartnerWasCreated $event): void
    {
        $this->uuid         = $event->uuid;
        $this->inn          = $event->inn;
        $this->phone        = $event->phone;
        $this->bik          = $event->bik;
        $this->kpp          = $event->kpp;
        $this->bank         = $event->bank;
        $this->bankAccount  = $event->bankAccount;
        $this->regionCode     = $event->regionCode;
        $this->legalAddress = $event->legalAddress;
        $this->actualAddress = $event->actualAddress;
        $this->setCreatedAt($event->createdAt);

        if (!empty($event->user)) {
            $this->setUsers([$event->user]);
        }
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

    public function phone(): string
    {
        return $this->email->toString();
    }

    public function uuid(): string
    {
        return $this->uuid->toString();
    }

    public function setUsers($users)
    {
        $this->users = new ArrayCollection($users);
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }
}
