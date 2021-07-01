<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\University;
use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Utils\SerializerGroups;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table("student")
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="User")
     *
     * @Serializer\Type(User::class)
     * @Serializer\Groups(groups={SerializerGroups::READ})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Classroom")
     *
     * @Serializer\Type(User::class)
     * @Serializer\Groups(groups={SerializerGroups::READ})
     */
    private $classroom;

    /**
     * @ORM\ManyToOne(targetEntity="University")
     *
     * @Serializer\Type(University::class)
     * @Serializer\Groups(groups={SerializerGroups::READ})
     */
    private $institute;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getClassroom()
    {
        return $this->classroom;
    }

    /**
     * @param mixed $classroom
     */
    public function setClassroom($classroom): void
    {
        $this->classroom = $classroom;
    }

    /**
     * @return mixed
     */
    public function getInstitute()
    {
        return $this->institute;
    }

    /**
     * @param mixed $institute
     */
    public function setInstitute($institute): void
    {
        $this->institute = $institute;
    }

}
