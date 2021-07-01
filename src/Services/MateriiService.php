<?php


namespace App\Services;


use App\Entity\Subjects;
use App\Entity\User;
use App\Repository\SubjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;

class MateriiService extends AbstractService
{
    private $twig;
    private $subjectRepo;
    public function __construct(SubjectsRepository $repository, Environment $twig)
    {
        parent::__construct($repository);
        $this->twig = $twig;
        $this->subjectRepo = $repository;
    }

    public function getAll() : array
    {
        $materii = $this->repository->findAll();
        return $materii;
    }

    public function deleteSubject($id, EntityManagerInterface $emi)
    {
        /** @var Subjects $subject */
        $subject = $this->subjectRepo->findOneBy(['id'=>$id]);
        $emi->remove($subject);
        $emi->flush();
    }

}