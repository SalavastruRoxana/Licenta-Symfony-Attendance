<?php


namespace App\Controller;


use Cassandra\Exception\ValidationException;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    private $serializer;
    private $validator;

    /**
     * BaseController constructor.
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface  $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    protected function createResponse($content, $groups = [], $includeNull = false, $format = 'json'): Response
    {
        $response = $this->serializer->serialize($content, $format,
            count($groups) > 0 ? (SerializationContext::create()->setGroups($groups)->setSerializeNull($includeNull)) : null);
        return new Response($response, 200, ['Content-Type' => 'text/html']);
    }

    protected function validatePayload($payload, $model, $groups = [Constraint::DEFAULT_GROUP], $format = 'json')
    {
        try {
            $payload = $this->serializer->deserialize($payload, $model, $format, DeserializationContext::create()->setGroups($groups));
        } catch (Exception $e) {
            throw new ValidationException($this->serializer->serialize($this->formatErrors(['body' => $e->getMessage()]), $format));
        }

        $errors = $this->validator->validate($payload, null, $groups);
        if (count($errors)) {
            throw new ValidationException($this->serializer->serialize($this->formatErrors($errors), $format));
        }

        return $payload;
    }


    protected function validateForm(Request $request, $form, $model, $groups = [Constraint::DEFAULT_GROUP], $format = 'json') {
        $form = $this->createForm($form, $model, ['validation_groups' => $groups]);
        $form->submit($request->query->all());
        if (!$form->isValid()) {
            throw new ValidationException($this->serializer->serialize($this->formatErrors($form->getErrors(true)), $format));
        }
        return $model;
    }
}