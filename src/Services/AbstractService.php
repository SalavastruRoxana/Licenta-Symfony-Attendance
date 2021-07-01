<?php


namespace App\Services;


use App\Repository\AbstractRepository;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class AbstractService
{
    protected $repository;

    public function __construct(AbstractRepository $repository)
    {
        $this->repository = $repository;
    }

    public function add($entity) {
        $this->repository->save($entity);
    }

    public function delete($entity) {
        $this->repository->delete($entity);
    }

    // only for account, content, category and contentSeries
    public function softDelete($entity) {
        $entity->setDeletedAt(new \DateTime(date("Y-m-d H:i:s")));
        $this->repository->save($entity);
    }

    public function refresh($entity) {
        $this->repository->refresh($entity);
    }

    public function getBy($criteria){
        return $this->repository->findBy($criteria);
    }

    public function getOneBy($criteria) {
        return $this->repository->findOneBy($criteria);
    }

    public function getOneByCaseInsensitive($criteria) {
        return $this->repository->findOneByCaseInsensitive($criteria);
    }

    public function getByMultipleIds($ids) {
        return $this->repository->findByMultipleIds($ids);
    }

    public function getAll() {
        return $this->repository->findAll();
    }

    public function response($template, $twig, $vars = []): Response
    {
        return new Response( $twig->render($template, $vars) );
    }

}