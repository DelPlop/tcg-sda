<?php

namespace App\Repository;

use App\Entity\ApplicationUser;
use App\Entity\UserOwnedCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method UserOwnedCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserOwnedCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserOwnedCard[]    findAll()
 * @method UserOwnedCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserOwnedCardRepository extends ServiceEntityRepository
{
    public const ITEM_PER_PAGE = 100;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOwnedCard::class);
    }

    public function findCards(ApplicationUser $user, int $offset): Paginator
    {
        $qb = $this->createQueryBuilder('oc')
            ->join('oc.card', 'c')
            ->join('c.edition', 'e')
            ->andWhere('oc.user = :user')
            ->setParameter('user', $user)
            ->addOrderBy('e.editionNumber', 'asc')
            ->addOrderBy('c.position', 'asc')
            ->setMaxResults(self::ITEM_PER_PAGE)
            ->setFirstResult($offset);

        return new Paginator($qb->getQuery());
    }
}
