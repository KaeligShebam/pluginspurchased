<?php

namespace App\Repository;

use DateTimeInterface;
use App\Entity\MonthlysSupport;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method MonthlysSupport|null find($id, $lockMode = null, $lockVersion = null)
 * @method MonthlysSupport|null findOneBy(array $criteria, array $orderBy = null)
 * @method MonthlysSupport[]    findAll()
 * @method MonthsCoMonthlysSupportntracts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonthlysSupportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonthlysSupport::class);
    }

    /*

    /**
     * @return MonthlysSupport[]
     */
    public function findMonthlysSupportExpirationDate(DateTimeInterface $range = null): array
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
