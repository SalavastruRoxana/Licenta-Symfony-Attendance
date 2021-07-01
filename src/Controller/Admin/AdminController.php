<?php


namespace App\Controller\Admin;


use App\Controller\BaseController;
use App\Entity\Schedule;
use App\Entity\User;
use App\Form\UnivType;
use App\Models\UnivModel;
use App\Repository\ProfessorRepository;
use App\Repository\StudentRepository;
use App\Repository\SubjectsRepository;
use App\Repository\UnivRepository;
use App\Repository\UserRepository;
use App\Services\Admin\AdminService;
use App\Services\Classes\ClassService;
use App\Services\MateriiService;
use App\Services\ScheduleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class AdminController extends BaseController
{
    private $service;
    private $twig;
    private $classService;

    public function __construct(Environment $twig, UnivRepository $repo, ClassService $classService,
                                MateriiService $materiiSerrvice, ScheduleService $scheduleService,
                                ProfessorRepository $professorRepository, SubjectsRepository $subjectsRepository,
                                MateriiService $materiiService, StudentRepository $studentRepo,
                                UserRepository $userRepository, UnivRepository $univRepository)
    {
        $this->twig = $twig;
        $this->service = new AdminService($this->twig, $repo, $classService, $materiiSerrvice, $scheduleService,
                                            $professorRepository, $materiiSerrvice, $studentRepo, $userRepository,
                                            $univRepository);
    }

    /**
     * @Route(path="/univ/edit",
     *     name = "edit_admin_univ")
     * @IsGranted("ROLE_ADMIN")
     */
    function univEdit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $univ = new UnivModel();
        $form = $this->createForm(UnivType::class,
            $univ,
            array(
                'action' => $this->generateUrl('edit_admin_univ'),
                'method' => 'POST'
            ));
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->adminEditUnivPage($request, $form, $univ, $entityManager, $this->getDoctrine(),  $user);
    }

    /**
     * @Route(path="/univ/students",
     *     name = "univ_students")
     * @IsGranted("ROLE_ADMIN")
     */
    function univStudents(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->univStudents($request, $user);
    }

    /**
     * @Route(path="/univ",
     *     name = "get_admin_univ")
     * @IsGranted("ROLE_ADMIN")
     */
    function univ(Request $request): Response
    {
        return $this->service->adminUnivPage($request, $this->getDoctrine(), $this->getUser());
    }

    /**
     * @Route(path="/univ/Professori",
     *     name = "get_admin_Professori")
     * @IsGranted("ROLE_ADMIN")
     */
    function Professori(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->adminProfPage($request, $user);
    }

    /**
     * @Route(path="/univ/schedule",
     *     name = "univ_schedule")
     * @IsGranted("ROLE_ADMIN")
     */
    function schedule(Request $request): Response
    {
        $schedule = new Schedule();
        $form = $this->createFormBuilder($schedule)
            ->add('classroom', TextType::class)
            ->add('salvare', SubmitType::class)
            ->getForm();
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->orar($request, $schedule, $form, $user);
    }



}