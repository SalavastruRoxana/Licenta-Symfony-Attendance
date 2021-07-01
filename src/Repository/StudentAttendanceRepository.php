<?php

namespace App\Repository;

use App\Entity\StudentAttendance;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


class StudentAttendanceRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, StudentAttendance::class);
    }

}
