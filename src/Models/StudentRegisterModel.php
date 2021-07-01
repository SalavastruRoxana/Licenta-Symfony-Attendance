<?php


namespace App\Models;

use App\Utils\SerializerGroups;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class StudentRegisterModel
{
    /**
     * @Assert\NotBlank
     * @Serializer\Groups(groups={SerializerGroups::READ,SerializerGroups::REGISTER})
     * @Serializer\SerializedName("lastName")
     */
    private $lastName;

    /**
     * @Assert\NotBlank
     * @Serializer\Groups(groups={SerializerGroups::READ,SerializerGroups::REGISTER})
     * @Serializer\SerializedName("firstName")
     */
    private $firstName;

    /**
     * @Assert\NotBlank
     * @Serializer\Groups(groups={SerializerGroups::READ,SerializerGroups::REGISTER})
     */
    private $type;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Serializer\Groups(groups={SerializerGroups::READ,SerializerGroups::REGISTER})
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "password trebuie sa contina cel putin {{ limit }} caractere"
     * )
     * @Serializer\Groups(groups={SerializerGroups::REGISTER})
     */
    private $password;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
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
}