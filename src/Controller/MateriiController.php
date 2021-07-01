<?php


namespace App\Controller;


use App\Entity\Classroom;
use App\Entity\Subjects;
use App\Repository\SubjectsRepository;
use App\Services\MateriiService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class MateriiController extends BaseController
{
    private $service;

    public function __construct(SubjectsRepository $mr, Environment $twig)
    {
        $this->service = new MateriiService($mr, $twig);
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

    /**
     * @Route (path="/profs/subject/delete/{id}",
     *     methods={"GET"},
     *     name = "delete_subject")
     * @IsGranted("ROLE_PROFESSOR")
     */
    public function deleteSubject(Request $request, $id, EntityManagerInterface $emi){
        $this->service->deleteSubject($id, $emi);
        return $this->redirect('/profs/profile');
    }


}