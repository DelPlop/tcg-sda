<?php

namespace App\Repository;

use App\Entity\ApplicationUser;
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

    public function findCards(ApplicationUser $user): array
    {
        return $this->createQueryBuilder('wc')
            ->join('wc.card', 'c')
            ->andWhere('wc.user = :user')
            ->setParameter('user', $user)
            ->addOrderBy('c.edition', 'asc')
            ->addOrderBy('c.position', 'asc')
            ->getQuery()
            ->getResult();
    }
}
