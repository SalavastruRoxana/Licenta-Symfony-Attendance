<?php

namespace App\Entity;

use App\Repository\UnivRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Utils\SerializerGroups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnivRepository", repositoryClass=UnivRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class University
{

    public function __construct()
    {
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeleUniversitatii;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeleFacultatii;

    /**
     * @ORM\Column(type="string", length=21)
     */
    private $nrTel;

    /**
     * @ORM\Column(type="string", length=21)
     */
    private $adresa;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_admin;

    /**
     * @ORM\Column(type="integer")
     */
    private $codulInstitutiei;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNumeleUniversitatii()
    {
        return $this->numeleUniversitatii;
    }

    /**
     * @param mixed $numeleUniversitatii
     */
    public function setNumeleUniversitatii($numeleUniversitatii): void
    {
        $this->numeleUniversitatii = $numeleUniversitatii;
    }

    /**
     * @return mixed
     */
    public function getNumeleFacultatii()
    {
        return $this->numeleFacultatii;
    }

    /**
     * @param mixed $numeleFacultatii
     */
    public function setNumeleFacultatii($numeleFacultatii): void
    {
        $this->numeleFacultatii = $numeleFacultatii;
    }

    /**
     * @return mixed
     */
    public function getNrTel()
    {
        return $this->nrTel;
    }

    /**
     * @param mixed $nrTel
     */
    public function setNrTel($nrTel): void
    {
        $this->nrTel = $nrTel;
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
    public function getAdresa()
    {
        return $this->adresa;
    }

    /**
     * @param mixed $adresa
     */
    public function setAdresa($adresa): void
    {
        $this->adresa = $adresa;
    }

    public function getIdAdmin(): ?int
    {
        return $this->id_admin;
    }

    public function setIdAdmin(int $id_admin): self
    {
        $this->id_admin = $id_admin;

        return $this;
    }

    public function getCodulInstitutiei(): ?int
    {
        return $this->codulInstitutiei;
    }

    public function setCodulInstitutiei(int $codulInstitutiei): self
    {
        $this->codulInstitutiei = $codulInstitutiei;

        return $this;
    }
}
