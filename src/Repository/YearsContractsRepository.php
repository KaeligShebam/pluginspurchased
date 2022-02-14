<?php

namespace App\Repository;

use DateTimeInterface;
use App\Entity\YearsContracts;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method YearsContracts|null find($id, $lockMode = null, $lockVersion = null)
 * @method YearsContracts|null findOneBy(array $criteria, array $orderBy = null)
 * @method YearsContracts[]    findAll()
 * @method YearsContracts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YearsContractsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, YearsContracts::class);
    }

    // /**
    //  * @return YearsContracts[] Returns an array of YearsContracts objects
    //  */
    /*

    /**
     * @return YearsContracts[]
     */
    public function findYearsContractsExpirationDate(DateTimeInterface $range = null): array
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
