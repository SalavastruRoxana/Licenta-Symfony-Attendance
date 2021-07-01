<?php

namespace App\Repository;

use App\Entity\Professor;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 */
class ProfessorRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Professor::class);
    }

}
