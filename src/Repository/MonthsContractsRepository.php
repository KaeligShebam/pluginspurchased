<?php

namespace App\Repository;

use DateTimeInterface;
use App\Entity\MonthsContracts;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method MonthsContracts|null find($id, $lockMode = null, $lockVersion = null)
 * @method MonthsContracts|null findOneBy(array $criteria, array $orderBy = null)
 * @method MonthsContracts[]    findAll()
 * @method MonthsContracts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonthsContractsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonthsContracts::class);
    }

    /*

    /**
     * @return MonthsContracts[]
     */
    public function findMonthsContractsExpirationDate(DateTimeInterface $range = null): array
    {
        if (null === $range) {
            $range = new \DateTime('+90 days');
        }

        return $this->createQueryBuilder('u')
            ->where("DATE_FORMAT(u.enddate,'%d-%m-%d') = :range")
            ->setParameter('range', $range->format('d-m-d'))
            ->getQuery()
            ->getResult();
    }
}
