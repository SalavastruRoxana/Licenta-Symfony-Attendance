<?php


namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;


class HomeService extends AbstractService
{

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getHomePage(): Response
    {
        $content =  $this->twig->render('home.html.twig');
        return new Response($content);
    }

    public function getProfilePage(): Response
    {
        $content =  $this->twig->render('profile/profile.html.twig');

        return new Response($content);
    }
}