<?php


namespace App\Services;

use App\Entity\Student;
use App\Entity\User;
use App\Models\StudentRegisterModel;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class AuthenticationService extends AbstractService
{

    private $twig;
    private $studentRepository;

    public function __construct(Environment $twig, UserRepository $userRepository, StudentRepository $studentRepository)
    {
        parent::__construct($userRepository);

        $this->studentRepository = $studentRepository;
        $this->twig = $twig;
    }

    public function registerStudent(Request $request, EntityManagerInterface $entityManager,
                                    ValidatorInterface $validator, $form, $studentModel,
                                    UserPasswordEncoderInterface $passwordEncoder)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            #cast de la modelStudent la entityStudent
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            $studentModel = $serializer->serialize($studentModel, 'json');
            /** @var User $user */
            $user = $serializer->deserialize($studentModel,User::class, 'json');
            $studentModel = $serializer->deserialize($studentModel, StudentRegisterModel::class, 'json');

            // set roles
            $roles = $user->getRoles();
            $roles[] = $studentModel->getType();
            $user->setRoles($roles);

            // encrypt password
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            $entityManager->persist($user);

            /** @var Student $student */
            $student = new Student();
            $student->setUser($user);
            $this->studentRepository->persist($student);


            $entityManager->flush();
            return $this->response('login/registerSuccess.html.twig', $this->twig, ['email' => $user->getEmail()]);
        }else{
            $errors = $validator->validate($studentModel);
            if (count($errors) > 0) {
                if (count($errors) > 0) {
                    return $this->response('login/registerStudent.html.twig',$this->twig, [
                        'form' => $form->createView(),
                        'errors' => $errors,
                    ]);
                }
            }
        }
        return null;
    }

    public function loginStudent(Request $request,
                                 ValidatorInterface $validator,
                                 $form,$student,$passwordEncoder,$encoder, $getDoctrine)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $student = $getDoctrine
                ->getRepository(Student::class)
                ->findOneBy(['email' => $user->getEmail()]);

            if (!$student) {
                $content =  $this->twig->render('login/loginStudent.html.twig');
                return new Response($content);
            }

            $isValid = $passwordEncoder->isPasswordValid($student, $user->getPassword() );

            if (!$isValid) {
                //throw new BadCredentialsException();
                return $this->response('login/loginStudent.html.twig',$this->twig, [
                    'form' => $form->createView()
                ]);
            }

            $this->newTokenAction($student, $encoder);

            $content =  $this->twig->render('profile/profile.html.twig');
            return new Response($content);
        }

        $errors = $validator->validate($student);
        if (count($errors) > 0) {
            if (count($errors) > 0) {
                return $this->response('login/loginStudent.html.twig',$this->twig, [
                    'form' => $form->createView(),
                    'errors' => $errors,
                ]);
            }
        }
    }

    public function newTokenAction($user, $encoder)
    {
        $token = $encoder->encode([
            'username' => $user->getEmail(),
            'exp' => time() + 3600 // 1 hour expiration
        ]);

        return new JsonResponse(['token' => $token]);
    }

    public function getTwig(){
        return $this->twig;
    }

}