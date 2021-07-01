<?php


namespace App\EventListener;

use App\Services\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{

    public function __construct()
    {
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();

//        $user = $this->userService->getOneBy([]);

        $data['user']['account'] = [

        ];

        $event->setData($data);
    }
}