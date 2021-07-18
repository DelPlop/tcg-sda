<?php

namespace App\Repository;

use App\Entity\Signet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Signet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Signet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Signet[]    findAll()
 * @method Signet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Signet::class);
    }
}
