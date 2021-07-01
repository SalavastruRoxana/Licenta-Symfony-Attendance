<?php


namespace App\Models;


use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class StudentLoginModel
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Groups ("login")
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Parola trebuie sa contina cel putin {{ limit }} caractere"
     * )
     * @Groups ("login")
     */
    private $password;

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