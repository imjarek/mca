<?php

declare(strict_types=1);

namespace App\Domain\Partner\Specification;

use App\Domain\Partner\Exception\InnAlreadyExistException;
use App\Domain\Partner\Repository\CheckPartnerByInnInterface;
use App\Shared\Domain\Specification\AbstractSpecification;
use App\Domain\Partner\ValueObject\Inn;
use App\Domain\Partner\Exception\EmailAlreadyExistException;
use App\Domain\Partner\Repository\CheckUserByEmailInterface;
use Doctrine\ORM\NonUniqueResultException;

final class UniqueInnSpecification extends AbstractSpecification implements UniqueInnSpecificationInterface
{
    private CheckPartnerByInnInterface $checkPartnerByInn;

    public function __construct(CheckPartnerByInnInterface $checkPartnerByInn)
    {
        $this->checkPartnerByInn = $checkPartnerByInn;
    }

    /**
     * @throws EmailAlreadyExistException
     */
    public function isUnique(Inn $inn): bool
    {
        return $this->isSatisfiedBy($inn);
    }

    /**
     * @param Email $value
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy($value): bool
    {
        try {
            if ($this->checkPartnerByInn->existsInn($value)) {
                throw new InnAlreadyExistException();
            }
        } catch (NonUniqueResultException $e) {
            throw new InnAlreadyExistException();
        }

        return true;
    }
}
