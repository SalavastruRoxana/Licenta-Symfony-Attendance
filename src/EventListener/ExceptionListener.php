<?php

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use App\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $throwable = $event->getThrowable();
        if ($throwable instanceof ValidationException) {
            $event->setResponse(new Response($throwable->getMessage(), 418, ['Content-Type' => 'application/json']));
        }
    }
}