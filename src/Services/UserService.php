<?php


namespace App\Services;


use App\Entity\Student;
use App\Entity\User;
use App\Repository\ProfessorRepository;
use App\Repository\UserRepository;
use App\Services\Admin\AdminService;
use App\Services\Classes\ClassService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class UserService extends AbstractService
{
    private $twig;
    private $classService;
    private $studentService;

    public function __construct(Environment $twig,UserRepository $userRepository, ClassService $classService,
                                StudentService $studentService)
    {
        parent::__construct($userRepository);

        $this->classService = $classService;
        $this->twig = $twig;
        $this->studentService = $studentService;
    }

    public function studentProfile(Request $request, User $user): Response
    {
        if ($request->isMethod('POST')) {
            $code = $request->request->get('cod');

            if ($this->classService->checkClassCode($code)){
                $stud = new Student();
                $stud->setIdGrupa(intval($code));
                $stud->setIdUniversitate($this->classService->getUnivCodeByClassCode($code));
                // TODO TREC AICI ID-UL STUD CONECTAT, si id-ul univ la care se afla

                $stud->setIdStudent($user->getId());
                $instituteId = $user->getId();
//                $instituteId = $this->studentService
                $stud->setIdUniversitate();

                $this->repository->persist($stud);
                $this->repository->flush();
                $content =  $this->twig->render('students/profile.html.twig');
                return new Response($content);
            }
            else
            {
                $content =  $this->twig->render('students/cod-univ.html.twig',
                    ['error_invalid' => 'invalid']);
                return new Response($content);
            }
        }
        //TODO DACA STUD NU E REPARTIZAT -> cod-univ.html.twig ELSE profile.html.twig
        $content =  $this->twig->render('students/cod-univ.html.twig',
        ['error_invalid'=>null]);
        return new Response($content);
    }
}