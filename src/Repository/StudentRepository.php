<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 */
class StudentRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Student::class);
    }

}
