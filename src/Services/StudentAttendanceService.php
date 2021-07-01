<?php

namespace App\Services;

use App\Repository\StudentAttendanceRepository;

class StudentAttendanceService extends AbstractService
{
    public function __construct(StudentAttendanceRepository $repository)
    {
        parent::__construct($repository);
    }
}