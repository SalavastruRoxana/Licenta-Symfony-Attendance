<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Utils\SerializerGroups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table("user")
 */
class User implements UserInterface
{
    CONST ROLE_STUDENT = 'ROLE_STUDENT';
    CONST ROLE_PROFESSOR = 'ROLE_PROFESSOR';
    CONST ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Column(type="simple_array")
     *
     * @Serializer\Groups(groups={SerializerGroups::READ})
     * @Serializer\Type("array<string>")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = array('ROLE_USER');
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\SerializedName("firstName")
     * @Serializer\Type("string")
     * @Serializer\Groups(groups={SerializerGroups::READ,SerializerGroups::REGISTER,SerializerGroups::EDIT})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\SerializedName("firstName")
     * @Serializer\Type("string")
     * @Serializer\Groups(groups={SerializerGroups::READ,SerializerGroups::REGISTER,SerializerGroups::EDIT})
     */
    private $lastName;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email
     *
     * @Serializer\Type("string")
     * @Serializer\Groups(groups={SerializerGroups::READ,SerializerGroups::REGISTER,SerializerGroups::LOGIN})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50 )
     * @Assert\Length(
     *      min = 8,
     *      max = 50,
     *      minMessage = "Your password must be at least {{ limit }} characters long",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     *
     * @Serializer\Type("string")
     * @Serializer\Groups(groups={SerializerGroups::READ,SerializerGroups::REGISTER,SerializerGroups::LOGIN})
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Groups(groups={SerializerGroups::INFO})
     * @Serializer\Type("datetime")
     */
    private $createdAt;

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
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
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


}
