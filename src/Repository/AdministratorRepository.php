<?php

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 */
class AdministratorRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Admin::class);
    }

}
