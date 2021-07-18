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
}
