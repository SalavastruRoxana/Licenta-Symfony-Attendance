<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Utils\SerializerGroups;

/**
 * @ORM\Entity(repositoryClass=ScheduleRepository::class)
 */
class Schedule
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
    private $idUniv;

    /**
     * @ORM\Column(type="integer")
     */
    private $idProf;

    /**
     * @ORM\Column(type="integer")
     */
    private $idClass;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $classroom;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $subject;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $day;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdProf(): ?int
    {
        return $this->idProf;
    }

    public function setIdProf(int $idProf): self
    {
        $this->idProf = $idProf;

        return $this;
    }

    public function getIdClass(): ?int
    {
        return $this->idClass;
    }

    public function setIdClass(int $idClass): self
    {
        $this->idClass = $idClass;

        return $this;
    }

    public function getClassroom(): ?string
    {
        return $this->classroom;
    }

    public function setClassroom(string $classroom): self
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }
}
