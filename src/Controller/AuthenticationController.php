<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Form\StudentLoginType;
use App\Form\StudentType;
use App\Models\StudentLoginModel;
use App\Models\StudentRegisterModel;
use App\Services\AuthenticationService;
use App\Utils\SerializerGroups;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class AuthenticationController extends BaseController
{
    private $service;
    private $passwordEncoder;
    private $encoder;

    /**
     * AuthenticationController constructor.
     */
    public function __construct(SerializerInterface          $serializer,
                                ValidatorInterface           $validator,
                                Environment                  $twig,
                                UserPasswordEncoderInterface $passwordEncoder,
                                JWTEncoderInterface          $encoder,
                                AuthenticationService        $service)
    {
        parent::__construct($serializer, $validator);
        $this->twig = $twig;
        $this->service = $service;
        $this->passwordEncoder = $passwordEncoder;
        $this->encoder = $encoder;
    }



    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/student/register",
     *     methods={"POST"},
     *     name = "post_register_student")
     */
    function postRegisterStudent(Request $request, ValidatorInterface  $validator, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncode): Response
    {
        $student = new StudentRegisterModel();
        $form = $this->createForm(StudentType::class,
            $student,
            array('action' => $this->generateUrl('post_register_student'),
                'method' => 'POST')
        );
        return $this->service->registerStudent($request, $entityManager, $validator, $form, $student, $passwordEncode);
    }

    /**
     * @Route("/loginSuccess",
     *     methods={"GET"},
     *     name = "login_success")
     */
    function loginSuccess(Request $request): Response
    {
        $role = $this->getUser()->getRoles();
        if(array_search("ROLE_STUDENT",$role))
        {
            return $this->redirectToRoute('student_profile');
        }elseif (array_search("ROLE_ADMIN",$role))
        {
            return $this->redirectToRoute('get_admin_univ');
        }elseif (array_search("ROLE_PROFESSOR",$role))
        {
            return $this->redirectToRoute('profs_profile');
        }

        return $this->render('login/loginStudent.html.twig');
    }

    /**
     * @Route("/student/register",
     *     methods={"GET"},
     *     name = "get_register_student")
     */
    function getRegisterStudent(Request $request): Response
    {
        $student = new StudentRegisterModel();

        $form = $this->createForm(StudentType::class,
            $student,
            array(
                'action' => $this->generateUrl('post_register_student'),
                'method' => 'POST'
            ));

        return $this->render('login/registerStudent.html.twig', [
            'form' => $form->createView(),
            'errors' => null
        ]);
    }

}