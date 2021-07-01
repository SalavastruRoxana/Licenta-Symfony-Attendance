<?php

namespace App\Services;

use App\Repository\ProfessorAttendanceRepository;

class ProfessorAttendanceService extends AbstractService
{
    public function __construct(ProfessorAttendanceRepository $repository)
    {
        parent::__construct($repository);
    }
}