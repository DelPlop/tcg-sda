<?php

namespace App\Repository;

use App\Entity\UserWantedCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserWantedCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserWantedCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserWantedCard[]    findAll()
 * @method UserWantedCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserWantedCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserWantedCard::class);
    }

    // /**
    //  * @return UserWantedCard[] Returns an array of UserWantedCard objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserWantedCard
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
