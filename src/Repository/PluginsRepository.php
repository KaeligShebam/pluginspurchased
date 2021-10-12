<?php

namespace App\Repository;

use App\Entity\Plugins;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Plugins|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plugins|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plugins[]    findAll()
 * @method Plugins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PluginsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plugins::class);
    }

    // /**
    //  * @return Plugins[] Returns an array of Plugins objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Plugins
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
