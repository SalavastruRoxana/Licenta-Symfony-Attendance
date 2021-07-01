<?php

namespace App\Entity;

use App\Repository\SubjectsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Utils\SerializerGroups;

/**
 * @ORM\Entity(repositoryClass=SubjectsRepository::class)
 */
class Subjects
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idProfessor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nume;

    /**
     * @ORM\Column(type="integer")
     */
    private $idInstitutie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProfessor(): ?int
    {
        return $this->idProfessor;
    }

    public function setIdProfessor(int $idProfessor): self
    {
        $this->idProfessor = $idProfessor;

        return $this;
    }

    public function getNume(): ?string
    {
        return $this->nume;
    }

    public function setNume(string $nume): self
    {
        $this->nume = $nume;

        return $this;
    }

    public function getIdInstitutie(): ?int
    {
        return $this->idInstitutie;
    }

    public function setIdInstitutie(int $idInstitutie): self
    {
        $this->idInstitutie = $idInstitutie;

        return $this;
    }
}
