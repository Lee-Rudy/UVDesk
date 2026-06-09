<?php

namespace App\Repository;

use App\Entity\UvdeskTicketReference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UvdeskTicketReferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UvdeskTicketReference::class);
    }
}
