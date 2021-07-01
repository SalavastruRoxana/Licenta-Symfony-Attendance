<?php

namespace App\Entity;

use App\Repository\ProfessorAttendanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessorAttendanceRepository::class)
 */
class ProfessorAttendance
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
    private $classroomId;

    /**
     * @ORM\Column(type="integer")
     */
    private $professorId;

    /**
     * @ORM\Column(type="integer")
     */
    private $subjectId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $longitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassroomId(): ?int
    {
        return $this->classroomId;
    }

    public function setClassroomId(int $classroomId): self
    {
        $this->classroomId = $classroomId;

        return $this;
    }

    public function getProfessorId(): ?int
    {
        return $this->professorId;
    }

    public function setProfessorId(int $professorId): self
    {
        $this->professorId = $professorId;

        return $this;
    }

    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }

    public function setSubjectId(int $subjectId): self
    {
        $this->subjectId = $subjectId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('Europe/Bucharest'));
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
