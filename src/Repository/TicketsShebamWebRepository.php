<?php

namespace App\Repository;

use DateTimeInterface;
use App\Entity\TicketsShebamWeb;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method TicketsShebamWeb|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketsShebamWeb|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketsShebamWeb[]    findAll()
 * @method TicketsShebamWeb[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketsShebamWebRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketsShebamWeb::class);
    }

    // /**
    //  * @return TicketsShebamWeb[] Returns an array of TicketsShebamWeb objects
    //  */
    /*

    /**
     * @return TicketsShebamWeb[]
     */
    public function findTicketsShebamWebExpirationDate(DateTimeInterface $range = null): array
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
