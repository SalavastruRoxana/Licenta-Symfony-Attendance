<?php


namespace App\Services;


use App\Repository\ScheduleRepository;

class ScheduleService extends AbstractService
{
    public function __construct(ScheduleRepository $repository)
    {
        parent::__construct($repository);
    }
}