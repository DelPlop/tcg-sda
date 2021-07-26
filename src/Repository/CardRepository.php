<?php

namespace App\Repository;

use App\Entity\Card;
use App\Entity\Edition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public const ITEM_PER_PAGE = 100;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function getPaginator(Edition $edition, int $offset): Paginator
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.edition = :crit')
            ->setParameter('crit', $edition)
            ->orderBy('c.position', 'ASC')
            ->setMaxResults(self::ITEM_PER_PAGE)
            ->setFirstResult($offset);

        return new Paginator($qb->getQuery());
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
            ->getOneOrNullResult();
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
            ->getOneOrNullResult();
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
            ->getOneOrNullResult();
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
            ->getOneOrNullResult();
    }

    public function quickSearchCards(string $searchTerm): array
    {
        $terms = explode(' ', $searchTerm);
        $qb = $this
            ->createQueryBuilder('c');
        foreach ($terms as $term) {
            $qb->orWhere('c.code like :code')
                ->orWhere('c.name like :term')
                ->orWhere('c.text like :term')
                ->orWhere('c.quote like :term')
                ->setParameter('code', $term)
                ->setParameter('term', '%'.$term.'%');
        }
        $qb
            ->addOrderBy('c.edition', 'ASC')
            ->addOrderBy('c.position', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function searchCards(array $criteria): array
    {
        $qb = $this
            ->createQueryBuilder('c');

        // int
        if (isset($criteria['cost'])) {
            $qb->andWhere('c.cost = :cost')
                ->setParameter('cost', $criteria['cost']);
        }
        if (!empty($criteria['strength'])) {
            $qb->andWhere('c.strength = :strength')
                ->setParameter('strength', $criteria['strength']);
        }
        if (!empty($criteria['vitality'])) {
            $qb->andWhere('c.vitality = :vitality')
                ->setParameter('vitality', $criteria['vitality']);
        }
        if (!empty($criteria['resistance'])) {
            $qb->andWhere('c.resistance = :resistance')
                ->setParameter('resistance', $criteria['resistance']);
        }
        if (!empty($criteria['site_number'])) {
            $qb->andWhere('c.siteNumber = :site_number')
                ->setParameter('site_number', $criteria['site_number']);
        }
        if (!empty($criteria['shadow_number'])) {
            $qb->andWhere('c.shadowNumber = :shadow_number')
                ->setParameter('shadow_number', $criteria['shadow_number']);
        }

        // bool
        if (!empty($criteria['isUnique'])) {
            $qb->andWhere('c.isUnique = :unique')
                ->setParameter('unique', $criteria['isUnique'] == 'yes' ? true : false);
        }
        if (!empty($criteria['isRingBearer'])) {
            $qb->andWhere('c.isRingBearer = :ring_bearer')
                ->setParameter('ring_bearer', $criteria['isRingBearer'] == 'yes' ? true : false);
        }
        if (!empty($criteria['isTengwar'])) {
            $qb->andWhere('c.isTengwar = :tengwar')
                ->setParameter('tengwar', $criteria['isTengwar'] == 'yes' ? true : false);
        }
        if (!empty($criteria['isRf'])) {
            $qb->andWhere('c.isRf = :rf')
                ->setParameter('rf', $criteria['isRf'] == 'yes' ? true : false);
        }
        if (!empty($criteria['isRfa'])) {
            $qb->andWhere('c.isRfa = :rfa')
                ->setParameter('rfa', $criteria['isRfa'] == 'yes' ? true : false);
        }
        if (!empty($criteria['hasLocalImage'])) {
            $qb->andWhere('c.hasLocalImage = :local_image')
                ->setParameter('local_image', $criteria['hasLocalImage'] == 'yes' ? true : false);
        }

        // relations
        if ($criteria['rarity']->count() > 0) {
            $qb->andWhere('c.rarity IN (:rarity)')
                ->setParameter('rarity', $criteria['rarity']->toArray());
        }
        if ($criteria['edition']->count() > 0) {
            $qb->andWhere('c.edition IN (:edition)')
                ->setParameter('edition', $criteria['edition']->toArray());
        }
        if ($criteria['culture']->count() > 0) {
            $qb->andWhere('c.culture IN (:culture)')
                ->setParameter('culture', $criteria['culture']->toArray());
        }
        if ($criteria['type']->count() > 0) {
            $qb->andWhere('c.type IN (:type)')
                ->setParameter('type', $criteria['type']->toArray());
        }
        if ($criteria['subtype']->count() > 0) {
            $qb->andWhere('c.subtype IN (:subtype)')
                ->setParameter('subtype', $criteria['subtype']->toArray());
        }
        if ($criteria['signet']->count() > 0) {
            $qb->andWhere('c.signet IN (:signet)')
                ->setParameter('signet', $criteria['signet']->toArray());
        }
        if ($criteria['phases']->count() > 0) {
            $qb->leftJoin('c.phases', 'p')
                ->andWhere('p IN (:phase)')
                ->setParameter('phase', $criteria['phases']->toArray());
        }
        if ($criteria['tag']->count() > 0) {
            $qb->leftJoin('c.tags', 't')
                ->andWhere('t IN (:tag)')
                ->setParameter('tag', $criteria['tag']->toArray());
        }

        // string
        if (!empty($criteria['code'])) {
            $qb->andWhere('c.code like :code')
                ->setParameter('code', '%'.$criteria['code'].'%');
        }
        if (!empty($criteria['name'])) {
            $qb->andWhere('c.name like :name')
                ->setParameter('name', '%'.$criteria['name'].'%');
        }
        if (!empty($criteria['text'])) {
            $qb->andWhere('c.text like :text')
                ->setParameter('text', '%'.$criteria['text'].'%');
        }
        if (!empty($criteria['quote'])) {
            $qb->andWhere('c.quote like :quote')
                ->setParameter('quote', '%'.$criteria['quote'].'%');
        }

        // order
        $qb
            ->addOrderBy('c.edition', 'ASC')
            ->addOrderBy('c.position', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
