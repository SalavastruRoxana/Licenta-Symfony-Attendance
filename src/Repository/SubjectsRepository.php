<?php

namespace App\Repository;

use App\Entity\Subjects;
use Doctrine\ORM\EntityManagerInterface;


class SubjectsRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, Subjects::class);
    }

}
