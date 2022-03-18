<?php

namespace App\Repository;

use DateTimeInterface;
use App\Entity\Themes;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Themes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Themes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Themes[]    findAll()
 * @method Themes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Themes::class);
    }

    /**
     * @return Themes[]
     */
    public function findThemesExpirationDate(DateTimeInterface $range = null): array
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
