<?php


namespace App\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AbstractRepository extends EntityRepository
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager, $class) {
        parent::__construct($entityManager, $entityManager->getClassMetadata($class));
        $this->entityManager = $this->getEntityManager();
    }

    public function save($entity) {
        $this->entityManager->persist($entity);
        $this->entityManager->flush($entity);
        $this->entityManager->refresh($entity);
    }


    public function merge($entity){
        $this->entityManager->merge($entity);
    }

    public function persist($entity) {
        $this->entityManager->persist($entity);
    }

    public function flush() {
        $this->entityManager->flush();
    }

    public function delete($entity) {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function refresh($entity) {
        $this->entityManager->refresh($entity);
    }

    public function findOneByCaseInsensitive($criteria) {
        $qb = $this->createQueryBuilder("s");

        $qb->select()
            ->setMaxResults(1);

        foreach($criteria as $property => $value){
            $qb->andWhere("LOWER(s." . $property . ") = :$property")
                ->setParameter("$property", strtolower($value));
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findByMultipleIds($ids) {
        if($ids){
            $qb = $this->createQueryBuilder("s");
            return $qb->select()->where($qb->expr()->in("s.id", $ids))->getQuery()->getResult();
        } else {
            return null;
        }
    }

    public function findByMultipleNames($names) {
        if($names){
            $qb = $this->createQueryBuilder("s");
            $qb->setCacheable(true)
                ->setLifetime(86400);
            return $qb->select()->where($qb->expr()->in("s.name", $names))->getQuery()->getResult();
        } else {
            return null;
        }
    }
}