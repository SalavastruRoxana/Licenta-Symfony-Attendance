<?php

namespace App\Repository;

use App\Entity\ProfessorAttendance;
use Doctrine\ORM\EntityManagerInterface;


class ProfessorAttendanceRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, ProfessorAttendance::class);
    }

}
