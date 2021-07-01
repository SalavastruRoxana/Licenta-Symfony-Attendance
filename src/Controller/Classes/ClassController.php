<?php


namespace App\Controller\Classes;


use App\Controller\BaseController;
use App\Entity\Classroom;
use App\Entity\User;
use App\Repository\ClassroomRepository;
use App\Repository\UnivRepository;
use App\Services\Classes\ClassService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;


class ClassController extends BaseController
{
    private $service;

    public function __construct(ClassroomRepository $classroomRepository, Environment $twig, RouterInterface $router,
    UnivRepository $univRepository)
    {
        $this->service = new ClassService($classroomRepository, $twig, $router,$univRepository);
    }

    /**
     * @Route (path="/newClass",
     *     name = "new_class")
     * @IsGranted("ROLE_ADMIN")
     */
    public function newClass(Request $request)
    {
        $newClass = new Classroom();

        $form = $this->createFormBuilder($newClass)
            ->add('nume', TextType::class)
            ->add('salvare', SubmitType::class)
            ->getForm();
        /** @var User $user */
        $user = $this->getUser();
        return $this->service->newClass($form, $request, $newClass, $user);

    }

    /**
     * @Route (path="/editClass/{id}",
     *     name = "edit_class",
     *     methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function editClass(Request $request, $id)
    {
        $clasa = $this->getDoctrine()
            ->getRepository(Classroom::class)
            ->find($id);


        $form = $this->createFormBuilder($clasa)
            ->add('nume', TextType::class)
            ->add('salvare', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('get_admin_univ');
        }

        return $this->render('admin/edit-class.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route (path="/univ/clasa/{id}",
     *     methods={"DELETE"},
     *     name = "delete_class")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteClass(Request $request, $id){
        $clasa = $this->getDoctrine()
            ->getRepository(Classroom::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($clasa);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }


}