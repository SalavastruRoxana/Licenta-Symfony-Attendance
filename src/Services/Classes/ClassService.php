<?php


namespace App\Services\Classes;


use App\Entity\Classroom;
use App\Entity\User;
use App\Repository\ClassroomRepository;
use App\Repository\UnivRepository;
use App\Services\AbstractService;
use App\Services\Admin\AdminService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class ClassService  extends AbstractService
{
    private $router;
    private $twig;
    private $univRepo;

    public function __construct(ClassroomRepository $repository, Environment $twig, RouterInterface $router,
    UnivRepository $univRepository)
    {
        parent::__construct($repository);
        $this->twig = $twig;
        $this->router = $router;
        $this->univRepo = $univRepository;
    }
    public function getAllClasses($getDoctrine) : array
    {
        $classes = $getDoctrine->getRepository(Classroom::class)->findAll();
        return $classes;
    }

    public function newClass(\Symfony\Component\Form\FormInterface $form, \Symfony\Component\HttpFoundation\Request $request, Classroom $newClass,
    User $user)
    {
        $form ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $newClass = $form->getData();
            //TODO SA ADAUG IN CAMPUL IDuNIV, ID-UL UNIVERSITATII DETINUTE DE ADMIN CARE A FACUT INSERTUL
            $newClass->setCod($this->getNewClassCode());
            $newClass->setNrStudents(0);

            //am nev de iduniv/univ
            //Circular reference detected
            $univ = $this->univRepo->findOneBy(['id_admin'=>$user->getId()]);
            dump($univ);
            $newClass->setIdUniv($univ->getId());
            dump($newClass);
            $this->repository->persist($newClass);
            $this->repository->flush();
            return new RedirectResponse($this->router->generate('get_admin_univ'));

        }
        $content =  $this->twig->render('admin/new-class.html.twig', array(
            'form' => $form->createView()
        ));
        return new Response($content);
        //TODO: AICI SETEZ NR STUDENT DIN TABELA DE LA LINKUL UNIV/STUDENTS
        //return $this->render('admin/new-class.html.twig', array('form' => $form->createView()));

    }

    private function getNewClassCode()
    {
        $number = random_int(1000000, 9000000);
        $result = $this->repository->findOneBy(['cod'=>$number]);
        while ($result)
        {
            $number = random_int(1000000, 9000000);
            $result = $this->repository->findOneBy(['cod'=>$number]);
        }
        return $number;
    }

    public function checkClassCode($code)
    {
        return $this->repository->findOneBy(['cod' => intval($code)]);
    }

    public function getUnivCodeByClassCode($code)
    {
        $result =  $this->repository->findOneBy(['cod'=> $code]);
        return $result->getIdUniv() ;
    }


}