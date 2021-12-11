<?php

declare(strict_types=1);

namespace Symfony5\Http\UI\Responder;

use Pomodoro\Presentation\PresenterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

abstract class ApiResponder
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function respond(array $params, PresenterInterface $presenter, ?array $formErrors): Response
    {
        $statusCode = Response::HTTP_OK;

        if ($formErrors !== null && count($formErrors)>0) {
            $data = $formErrors;
            $statusCode = Response::HTTP_BAD_REQUEST;
        } else {
            $viewModel = $presenter->viewModel();
            $data = ['id' => $viewModel->id];

            if (count($viewModel->errors)>0) {
                $data = $viewModel->errors;
                $statusCode = Response::HTTP_BAD_REQUEST;
            }
        }
        return $this->send($params, $data, $statusCode);
    }

    private function send(array $params, array $data, int $statusCode): Response
    {
        $response = new Response(null, Response::HTTP_OK);
        $normalizedData = $this->serializer->normalize($data, 'json', $params);
        $contentType = $params['content-type'];
        $locale = $params['locale'];

        if ($contentType === '' || strtolower($contentType)  === 'application/json') {
            $response = new JsonResponse(
                $normalizedData,
                $statusCode
            );
        }

        if (strtolower($contentType) === 'application/xml') {
            $xml = $this->serializer->serialize(
                $normalizedData,
                'xml'
            );
            $response = new Response($xml, $statusCode);
            $response->headers->set('Content-Type', 'text/xml');
        }

        return $response;
    }
}
