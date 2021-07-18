<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function findFirstCard(Card $card): ?Card
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.edition = :edition')
            ->setParameter('edition', $card->getEdition())
            ->andWhere('c.isDisplayable = :active')
            ->setParameter('active', true)
            ->orderBy('c.position', 'asc')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function findPreviousCard(Card $card): ?Card
    {
        $position = $card->getPosition() - 1;
        if ($position <= 0) {
            $position = $card->getPosition();
        }

        return $this->createQueryBuilder('c')
            ->andWhere('c.edition = :edition')
            ->setParameter('edition', $card->getEdition())
            ->andWhere('c.isDisplayable = :active')
            ->setParameter('active', true)
            ->andWhere('c.position = :position')
            ->setParameter('position', $position)
            ->orderBy('c.position', 'asc')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function findNextCard(Card $card): ?Card
    {
        $position = $card->getPosition() + 1;
        if ($position > 365) {
            $position = $card->getPosition();
        }

        return $this->createQueryBuilder('c')
            ->andWhere('c.edition = :edition')
            ->setParameter('edition', $card->getEdition())
            ->andWhere('c.isDisplayable = :active')
            ->setParameter('active', true)
            ->andWhere('c.position = :position')
            ->setParameter('position', $position)
            ->orderBy('c.position', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function findLastCard(Card $card): ?Card
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.edition = :edition')
            ->setParameter('edition', $card->getEdition())
            ->andWhere('c.isDisplayable = :active')
            ->setParameter('active', true)
            ->orderBy('c.position', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
