<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 *
 */
class UserRepository extends AbstractRepository implements UserLoaderInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, User::class);
    }

    public function loadUserByUsername(string $username)
    {
        $qb = $this->createQueryBuilder('u');

        $qb->select()
            ->where("u.email = :email")
            ->setParameter(':email', $username)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
