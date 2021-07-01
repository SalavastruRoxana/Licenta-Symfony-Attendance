<?php

namespace App\Entity;

use App\Repository\ClassroomRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Utils\SerializerGroups;


/**
 * @ORM\Entity(repositoryClass=ClassroomRepository::class)
 */
class Classroom
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nume;

    /**
     * @ORM\Column(type="integer")
     */
    private $cod;

    /**
     * @ORM\Column(type="integer")
     */
    private $nrStudents;

    /**
     * @ORM\Column(type="integer")
     */
    private $idUniv;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCod(): ?int
    {
        return $this->cod;
    }

    public function setCod(int $cod): self
    {
        $this->cod = $cod;

        return $this;
    }

    public function getNrStudents(): ?int
    {
        return $this->nrStudents;
    }

    public function setNrStudents(int $nrStudents): self
    {
        $this->nrStudents = $nrStudents;

        return $this;
    }

    public function getIdUniv(): ?int
    {
        return $this->idUniv;
    }

    public function setIdUniv(int $idUniv): self
    {
        $this->idUniv = $idUniv;

        return $this;
    }
}
