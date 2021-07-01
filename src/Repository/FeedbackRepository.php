<?php

namespace App\Repository;

use App\Entity\Feedback;
use Doctrine\ORM\EntityManagerInterface;


class FeedbackRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Feedback::class);
    }
}
