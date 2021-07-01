<?php


namespace App\Controller;

use App\Services\HomeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends BaseController
{
    private $service;
    private $twig;

    /**
     * HomeController constructor.
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->service = new HomeService($this->twig);
    }

    /**
     * @Route("/home",
     *     methods={"GET"},
     *     name = "home")
     */
    function home(Request $request): Response
    {
        $response = $this->service->getHomePage();
        return $response;
    }
}