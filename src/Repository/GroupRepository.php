<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    // Example of a custom query method
    public function findByName(string $name)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.nomGroup = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }

    // You can add more custom methods as needed
}
