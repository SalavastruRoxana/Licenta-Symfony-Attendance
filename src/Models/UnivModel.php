<?php


namespace App\Models;

use Symfony\Component\Validator\Constraints as Assert;

class UnivModel
{
    /**
     * @Assert\NotBlank
     */
    private $numeleUniversitatii;

    /**
     * @Assert\NotBlank
     */
    private $numeleFacultatii;

    /**
     * @Assert\NotBlank
     */
    private $nrTel;

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
     * @Assert\NotBlank
     */
    private $adresa;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

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

}