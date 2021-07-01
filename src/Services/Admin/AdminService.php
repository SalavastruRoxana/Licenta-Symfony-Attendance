<?php


namespace App\Services\Admin;


use App\Entity\Classroom;
use App\Entity\Professor;
use App\Entity\Schedule;
use App\Entity\Student;
use App\Entity\Subjects;
use App\Entity\University;
use App\Entity\User;
use App\Repository\ProfessorRepository;
use App\Repository\StudentRepository;
use App\Repository\SubjectsRepository;
use App\Repository\UnivRepository;
use App\Repository\UserRepository;
use App\Services\AbstractService;
use App\Services\Classes\ClassService;
use App\Services\MateriiService;
use App\Services\ProfsService;
use App\Services\ScheduleService;
use App\Services\StudentService;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twig\Environment;

class AdminService  extends AbstractService
{
    private $twig;
    private $classService;
    private $materiiService;
    private $scheduleService;
    private $professorRepo;
    private $subjectService;
    private $studentsRepo;
    private $userRepo;
    private $univRepo;


    public function __construct(Environment $twig, UnivRepository $repository, ClassService $classService,
                                MateriiService $materiiService, ScheduleService $scheduleService,
                                ProfessorRepository $professorRepo, MateriiService $subjectService,
                                StudentRepository $studentRepo, UserRepository $userRepo, UnivRepository $univRepo)
    {
        parent::__construct($repository);
        $this->twig = $twig;
        $this->classService = $classService;
        $this->materiiService = $materiiService;
        $this->scheduleService = $scheduleService;
        $this->professorRepo = $professorRepo;
        $this->subjectService = $subjectService;
        $this->studentsRepo = $studentRepo;
        $this->userRepo = $userRepo;
        $this->univRepo = $univRepo;
    }

    public function adminEditUnivPage(Request $request, $form, $univ, EntityManagerInterface $entityManager,
                                      $getDoctrine, User $user): Response
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $id_manager = $user->getId();

            $entityManager2 = $getDoctrine->getManager();
            $univToFind = $entityManager2->getRepository(University::class)->findOneBy(array('id_admin' => $id_manager));

            if (!$univToFind) {
                $encoders = [new XmlEncoder(), new JsonEncoder()];
                $normalizers = [new ObjectNormalizer()];
                $serializer = new Serializer($normalizers, $encoders);
                $univ = $serializer->serialize($univ, 'json');
                $univ = $serializer->deserialize($univ,University::class, 'json');
                $univ->setIdAdmin($id_manager);
                $this->setUnivCode($univ, $getDoctrine);
                $entityManager->persist($univ);
                $entityManager->flush();
            }
            else
            {
                $entityManager = $getDoctrine->getManager();
                $entityManager->flush();
            }
            $content =  $this->twig->render('admin/univ-edit.html.twig', ['form' => $form->createView(),
                'errors' => null ]);
            return new Response($content);

        }
        $content =  $this->twig->render('admin/univ-edit.html.twig', ['form' => $form->createView(),
            'errors' => null ]);
        return new Response($content);
    }

    public function adminUnivPage(Request $request, $getDoctrine, User $user): Response
    {
        $idAdmin = $user->getId();

        /** @var University $university */
        $university1 = $this->univRepo->findBy(['id_admin'=>$idAdmin]);
        foreach ($university1 as $university) {
            $univName = $university->getNumeleUniversitatii();
            $facultyName = $university->getNumeleFacultatii();
            $nrTel = $university->getNrTel();
            $email = $university->getEmail();

            $classes = $this->classService->repository->findBy(['idUniv'=>$university->getId()]);

            $content = $this->twig->render('admin/univ.html.twig',
                ['errors' => null,
                    'classes' => $classes,
                    'univName' => $univName,
                    'facultyName' => $facultyName,
                    'nrTel' => $nrTel,
                    'univEmail' => $email]);
            return new Response($content);
        }
        $content = $this->twig->render('admin/univ.html.twig',
            ['errors' => null,
                'classes' => 'nimic gasit',
                'univName' => 'nimic gasit',
                'facultyName' => 'nimic gasit',
                'nrTel' => 'nimic gasit',
                'univEmail' => 'nimic gasit']);
        return new Response($content);
    }

    public function adminProfPage(Request $request, User $user): Response
    {
        $idAdmin = $user->getId();

        $profsList = [];
        $cod = 'Nu aveti inca codul, creati universitatea';

        /** @var University $university */
        $university1 = $this->univRepo->findBy(['id_admin'=>$idAdmin]);
        foreach ($university1 as $university) {
            $cod = $university->getCodulInstitutiei();
            $universityId = $university->getId();

            $allProfessors = $this->professorRepo->findBy(['institute'=>$universityId]);

            /** @var Professor $prof */
            foreach ($allProfessors as $prof) {
                /** @var User $profId */
                $profId = $prof->getUser();
                $profId = $profId->getId();

                /** @var User $prof */
                $profUser = $this->userRepo->findOneBy(['id'=>$profId]);
                dump($profUser);
                if ($profUser)
                {
                    $profName  = $profUser->getLastName().' '.$profUser->getFirstName();
                    $profEmail = $profUser->getEmail();

                    array_push($profsList,[
                        'name'=>$profName,
                        'email'=>$profEmail
                    ]);

                }
            }
        }

        $content =  $this->twig->render('admin/profs.html.twig',
            ['errors' => null,
                'profs'=>$profsList,
                'cod'=>$cod]);

        return new Response($content);
    }

    public function setUnivCode($univ, $getDoctrine){
        $number = random_int(100000,38983670);
        $entityManager2 = $getDoctrine->getManager();
        $univToFind = $entityManager2->getRepository(University::class)->findOneBy(array('codulInstitutiei' => $number));
        if(!$univToFind) {
            $univ->setCodulInstitutiei($number);
        }
    }

    public function checkUnivCode($code)
    {
        $result = $this->repository->findOneBy(array('codulInstitutiei' => $code));
        return $result;
    }

    public function univStudents(Request $request, User $user)
    {
        /** @var University $idUniv */
        $idUniv = $this->repository->findOneBy(['id_admin'=>$user->getId()]);
        $idUniv = $idUniv->getId();
        $classes = $this->classService->getBy(['idUniv'=>$idUniv]);
        $table = [];
        /** @var Classroom $class */
        foreach ($classes as $class)
        {
            $className = $class->getNume();
            $nrStudents = $class->getNrStudents();

            //get all students from this class
            $classId = $class->getId();
            $classCode = $class->getCod();
            $students = $this->studentsRepo->findBy(['classroom'=>$classId]);
            $studentsArray = [];
            /** @var Student $student */
            foreach ($students as $student)
            {
                /** @var User $userId */
                $userId = $student->getUser();
                $studentId = $student->getId();

                /** @var User $student */
                $student = $this->userRepo->findOneBy(['id'=>$userId->getId()]);
                $studentName = $student->getLastName().' '.$student->getFirstName();
                $studentEmail = $student->getEmail();

                array_push($studentsArray,
                [
                    'studentName' => $studentName,
                    'studentEmail' => $studentEmail,
                    'studentId' => $studentId
                ]);
            }
            array_push($table,
            [
                'className' => $className,
                'nrStudents' => $nrStudents,
                'classCode' => $classCode,
                'students' => $studentsArray
            ]);
        }
        $content =  $this->twig->render('admin/students.html.twig',
            ['errors' => null,
                'table' => $table]);
        return new Response($content);
    }

    public function orar(Request $request, $schedule, $form, User $user)
    {
        $form ->handleRequest($request);
        if ($request->isMethod('POST'))
        {
            $schedule = $this->requestToSchedule($request, $user);
            $this->repository->persist($schedule);
            $this->repository->flush();
        }
        $classes = $this->classService->repository->findAll();
        $materii = $this->materiiService->repository->findAll();
        $schedule = $this->scheduleService->repository->findAll();
        $content =  $this->twig->render('admin/orar.html.twig',
            ['errors' => null,
                'form'=>$form->createView(),
                'classes' => $classes,
                'materii'=>$materii,
                'schedule'=> $schedule]);
        return new Response($content);
    }

    public function requestToSchedule($request, User $user)
    {
        $idclasa = $request->get('clasa');
        $sala = $request->get('sala');
        $ora_inceput = $request->get('ora_inceput');
        $minut_inceput = $request->get('minut_inceput');
        $ora_sfarsit = $request->get('ora_sfarsit');
        $minut_sfarsit = $request->get('minut_sfarsit');
        $materie = $request->get('materia');
        $schedule = new Schedule();
        $schedule->setClassroom($sala);
        $schedule->setIdClass(intval($idclasa));
        $schedule->setSubject($materie);
        $schedule->setDay($request->get('ziua'));
        $schedule->setTime($ora_inceput.":".$minut_inceput."-".$ora_sfarsit.":".$minut_sfarsit);
        //TODO
        /** @var University $id_univ */
        $id_univ = $this->getOneBy(['id_admin'=>$user->getId()]);
        $id_univ = $id_univ->getId();
        /** @var Subjects $subject */
        $subject = $this->subjectService->getOneBy(['nume'=>$materie]);
        $id_prof = $subject->getIdProfessor();
        $schedule->setIdUniv($id_univ);
        $schedule->setIdProf($id_prof);
        return $schedule;
    }

}