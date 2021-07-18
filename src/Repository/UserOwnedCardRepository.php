<?php

namespace App\Repository;

use App\Entity\UserOwnedCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserOwnedCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserOwnedCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserOwnedCard[]    findAll()
 * @method UserOwnedCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserOwnedCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOwnedCard::class);
    }

    // /**
    //  * @return UserOwnedCard[] Returns an array of UserOwnedCard objects
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
    public function findOneBySomeField($value): ?UserOwnedCard
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
