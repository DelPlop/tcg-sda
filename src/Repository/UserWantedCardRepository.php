<?php

namespace App\Repository;

use App\Entity\ApplicationUser;
use App\Entity\UserWantedCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method UserWantedCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserWantedCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserWantedCard[]    findAll()
 * @method UserWantedCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserWantedCardRepository extends ServiceEntityRepository
{
    public const ITEM_PER_PAGE = 100;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserWantedCard::class);
    }

    public function findCards(ApplicationUser $user, int $offset): Paginator
    {
        $qb = $this->createQueryBuilder('wc')
            ->join('wc.card', 'c')
            ->join('c.edition', 'e')
            ->andWhere('wc.user = :user')
            ->setParameter('user', $user)
            ->addOrderBy('e.editionNumber', 'asc')
            ->addOrderBy('c.position', 'asc')
            ->setMaxResults(self::ITEM_PER_PAGE)
            ->setFirstResult($offset);

        return new Paginator($qb->getQuery());
    }
}
