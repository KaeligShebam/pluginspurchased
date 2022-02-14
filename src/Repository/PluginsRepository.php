<?php

namespace App\Repository;

use DateTimeInterface;
use App\Entity\Plugins;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

    /**
     * @return Plugins[]
     */
    public function findPluginsExpirationDate(DateTimeInterface $range = null): array
    {
        if (null === $range) {
            $range = new \DateTime('+90 days');
        }

        return $this->createQueryBuilder('u')
            ->where("DATE_FORMAT(u.expirationdate,'%d-%m-%d') = :range")
            ->setParameter('range', $range->format('d-m-d'))
            ->getQuery()
            ->getResult();
    }
}
