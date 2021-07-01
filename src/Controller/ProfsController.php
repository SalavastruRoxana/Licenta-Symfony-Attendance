<?php


namespace App\Controller;

use App\Entity\Subjects;
use App\Entity\Schedule;
use App\Entity\User;
use App\Repository\FeedbackRepository;
use App\Repository\ProfessorAttendanceRepository;
use App\Repository\ProfessorRepository;
use App\Services\Admin\AdminService;
use App\Services\Classes\ClassService;
use App\Services\MateriiService;
use App\Services\ProfsService;
use App\Services\ScheduleService;
use App\Services\StudentAttendanceService;
use App\Services\UserService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;

class ProfsController extends BaseController
{
    private $service;
    private $twig;
    private $materiiService;

    public function __construct(Environment $twig, ProfessorRepository $profsRepository,  AdminService $adminService,
                                MateriiService $materiiService, RouterInterface $router,ScheduleService $scheduleService,
                                ClassService $classService, ProfessorAttendanceRepository $par, StudentAttendanceService $sas,
                                UserService $userService, FeedbackRepository  $feedbackRepository)
    {
        $this->twig = $twig;
        $this->materiiService = $materiiService;
        $this->service = new ProfsService($this->twig, $profsRepository,  $adminService, $materiiService, $router,
        $scheduleService, $classService, $par, $sas, $userService, $feedbackRepository);
    }

    /**
     * @Route(path="/profs/profile",
     *     name = "profs_profile")
     * @IsGranted("ROLE_PROFESSOR")
     */
    function profProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->profProfilePage($request, $user);
    }

    /**
     * @Route(path="/profs/attendanceList",
     *     name = "profs_attendance_list")
     * @IsGranted("ROLE_PROFESSOR")
     */
    function profAttendanceList(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->profAttendaceList($request, $user);
    }

    /**
     * @Route(path="/profs/subject/{id}",
     *     name = "profs_edit_subject")
     *     methods={"GET","POST"})
     * @IsGranted("ROLE_PROFESSOR")
     */
    function profEditClass(Request $request, $id): Response
    {
        $materie = $this->getDoctrine()
            ->getRepository(Subjects::class)
            ->find($id);
        $form = $this->createFormBuilder($materie)
            ->add('nume', TextType::class)
            ->add('salvare', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('profs_profile');
        }

        return $this->render('profesori/edit-subject.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route (path="/newSubject",
     *     name = "new_subject")
     * @IsGranted("ROLE_PROFESSOR")
     */
    public function newSubject(Request $request)
    {
        $new = new Subjects();

        $form = $this->createFormBuilder($new)
            ->add('nume', TextType::class)
            ->add('salvare', SubmitType::class)
            ->getForm();
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->newSubject($request, $form, $new, $user);
    }

    /**
     * @Route(path="/profs/schedule",
     *     name = "profs_schedule")
     * @IsGranted("ROLE_PROFESSOR")
     */
    function schedule(Request $request): Response
    {
        $schedule = new Schedule();
        return $this->service->schedule($request);

    }

    /**
     * @Route(path="/profs/attendance",
     *     name = "profs_attendance")
     * @IsGranted("ROLE_PROFESSOR")
     */
    function attendance(Request $request): Response
    {
        /**@var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->service->attendance($request, $user);
    }

    /**
     * @Route (path="/profs/attendanceList/{id}",
     *     name = "profs_attendance_list_id",
     *     methods={"GET","POST"})
     * @IsGranted("ROLE_PROFESSOR")
     */
    public function attendanceListById($id) : Response
    {
        /**@var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->service->attendanceList($id);
    }


    /**
     * @Route (path="/profs/attendanceList/feedbcak/{id}",
     *     name = "profs_feedback",
     *     methods={"GET","POST"})
     * @IsGranted("ROLE_PROFESSOR")
     */
    public function feedback(Request $request, $id)
    {
        /**@var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->service->feedback( $user, $id, $request);
    }

    /**
     * @Route (path="/profs/attendanceList/invalidate/{id}",
     *     name = "profs_attendance_invalidate_id",
     *     methods={"GET", "POST"})
     * @IsGranted("ROLE_PROFESSOR")
     */
    public function invalidate (Request $request, $id)
    {
        /**@var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->service->invalidate($id);
    }

    /**
     * @Route (path="/profs/attendanceList/validate/{id}",
     *     name = "profs_attendance_validate_id",
     *     methods={"GET", "POST"})
     * @IsGranted("ROLE_PROFESSOR")
     */
    public function validate(Request $request, $id)
    {
        /**@var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->service->validate($id);
    }
}