<?php


namespace App\Controller;


use App\Entity\Student;
use App\Entity\User;
use App\Repository\FeedbackRepository;
use App\Repository\ProfessorRepository;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use App\Services\Admin\AdminService;
use App\Services\Classes\ClassService;
use App\Services\MateriiService;
use App\Services\ProfessorAttendanceService;
use App\Services\ScheduleService;
use App\Services\StudentAttendanceService;
use App\Services\StudentService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class StudentsController extends BaseController
{
    private $service;
    private $twig;

    public function __construct(StudentRepository $repository, Environment $twig, ClassService $classService,
                                AdminService $adminService, ScheduleService $scheduleService, ProfessorAttendanceService $pas,
                                StudentAttendanceService $sas, ProfessorRepository $professorRepository, MateriiService $ms,
                                UserRepository $ur, FeedbackRepository $feedbackRepository)
    {
        $this->twig = $twig;
        $this->service = new StudentService($repository, $twig, $classService, $adminService, $scheduleService, $pas, $sas,
                                            $professorRepository, $ms, $ur, $feedbackRepository);
    }

    /**
     * @Route(path="/student/profile",
     *     name = "student_profile")
     * @IsGranted("ROLE_STUDENT")
     */
    function studentProfile(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->studentProfile($request, $user );
    }

    /**
     * @Route(path="/student/schedule",
     *     name = "student_schedule")
     * @IsGranted("ROLE_STUDENT")
     */
    function studentSchedule(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->studentSchedule($request, $user );
    }

    /**
     * @Route(path="/student/attendance",
     *     name = "student_attendance")
     * @IsGranted("ROLE_STUDENT")
     */
    function studentAttendance(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->studentAttendance($request, $user);
    }

    /**
     * @Route (path="/univ/student/{id}",
     *     name = "delete_student")
     * @IsGranted("ROLE_STUDENT")
     */
    public function deleteStudent(Request $request, $id){
        $this->service->deleteStudent($id);
        return $this->redirect('/univ/students');
    }

    /**
     * @Route (path="/student/feedback",
     *     name = "student_feedback")
     * @IsGranted("ROLE_STUDENT")
     */
    public function studentFeedback(Request $request){

        /** @var User $user */
        return $this->service->studentFeedback($request, $this->getUser());
    }
}