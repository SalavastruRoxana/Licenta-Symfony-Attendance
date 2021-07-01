<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeedbackRepository::class)
 */
class Feedback
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
    private $studentAttendance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $remark;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     */
    private $mark;

    /**
     * @ORM\Column(type="integer")
     */
    private $bonus;


    /**
     * @ORM\Column(type="datetime")
     *
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentAttendance(): ?int
    {
        return $this->studentAttendance;
    }

    public function setStudentAttendance(int $studentAttendance): self
    {
        $this->studentAttendance = $studentAttendance;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark): void
    {
        $this->remark = $remark;
    }

    /**
     * @return mixed
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * @param mixed $mark
     */
    public function setMark($mark): void
    {
        $this->mark = $mark;
    }

    /**
     * @return mixed
     */
    public function getBonus()
    {
        return $this->bonus;
    }

    /**
     * @param mixed $bonus
     */
    public function setBonus($bonus): void
    {
        $this->bonus = $bonus;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime();
    }

}
