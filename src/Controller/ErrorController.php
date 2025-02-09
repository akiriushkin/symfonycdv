<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ErrorController extends AbstractController
{
    #[Route('/error', name: 'error')]
    public function __invoke(Request $request, Throwable $exception, LoggerInterface $logger): JsonResponse
    {
        $statusCode = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        $logger->error('Exception caught: ' . $exception->getMessage(), [
            'status' => $statusCode,
            'exception' => $exception->getTraceAsString(),
        ]);

        $debug = $this->getParameter('kernel.debug');
        $message = $exception->getMessage();

        $responseData = [
            'error' => true,
            'status' => $statusCode,
            'message' => $message,
        ];

        if ($debug) {
            $responseData['trace'] = $exception->getTrace();
        }

        return new JsonResponse($responseData, $statusCode);
    }
}
