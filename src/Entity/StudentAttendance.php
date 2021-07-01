<?php

namespace App\Entity;

use App\Repository\StudentAttendanceRepository;
use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Validator\Constraints\DateTime;
use DateTime;

/**
 * @ORM\Entity(repositoryClass=StudentAttendanceRepository::class)
 */
class StudentAttendance
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
    private $studentId;

    /**
     * @ORM\Column(type="integer")
     */
    private $attendanceId;

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

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $distance;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $distanceStatus;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $locationExposure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentId(): ?int
    {
        return $this->studentId;
    }

    public function setStudentId(int $studentId): self
    {
        $this->studentId = $studentId;

        return $this;
    }

    public function getAttendanceId(): ?int
    {
        return $this->attendanceId;
    }

    public function setAttendanceId(int $attendanceId): self
    {
        $this->attendanceId = $attendanceId;

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

    public function getDistance(): ?string
    {
        return $this->distance;
    }

    public function setDistance(string $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getDistanceStatus(): ?string
    {
        return $this->distanceStatus;
    }

    public function setDistanceStatus(string $distanceStatus): self
    {
        $this->distanceStatus = $distanceStatus;

        return $this;
    }

    public function getLocationExposure(): ?string
    {
        return $this->locationExposure;
    }

    public function setLocationExposure(string $locationExposure): self
    {
        $this->locationExposure = $locationExposure;

        return $this;
    }
}
