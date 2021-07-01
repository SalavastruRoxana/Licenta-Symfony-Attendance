<?php


namespace App\Controller;


use App\Repository\ProfessorRepository;
use App\Repository\ScheduleRepository;
use App\Services\Admin\AdminService;
use App\Services\MateriiService;
use App\Services\ProfsService;
use Twig\Environment;

class ScheduleController extends BaseController
{

    private $service;

    public function __construct(ScheduleRepository $repository)
    {
        $this->service = new ScheduleService($repository);
    }
}