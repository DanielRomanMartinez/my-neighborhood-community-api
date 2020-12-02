<?php

declare(strict_types=1);

namespace App\UI\Events\Listeners;

use App\Domain\Customer\Exception\CustomerAlreadyExistsException;
use App\Domain\Customer\Exception\CustomerByEmailAndDomainNotFoundException;
use App\Domain\Customer\Exception\CustomerCurrentPasswordDoesNotMatchException;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Domain\NotificationLog\Exception\NotificationLogNotFoundException;
use App\Infrastructure\ApiClient\ExpertSender\Exception\ExpertSenderException;
use App\Infrastructure\ApiClient\ExpertSender\Exception\SubscriberNotFoundException;
use App\Infrastructure\ApiClient\GeoPlugin\Exception\GeoPluginClientException;
use App\Infrastructure\ApiClient\GeoPlugin\Exception\GeoPluginClientInvalidIpException;
use App\Infrastructure\Http\Request\Exception\HttpRequestValidationException;
use App\Infrastructure\Security\Exception\InvalidCredentialsException;
use App\Shared\Domain\ValueObject\Exception\InvalidUuidException;
use Exception;
use InvalidArgumentException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class ExceptionListener
{
    private const REGISTERED_EXCEPTIONS = [
        BadRequestHttpException::class  => Response::HTTP_BAD_REQUEST,
        GeoPluginClientException::class => Response::HTTP_BAD_REQUEST,
        ExpertSenderException::class    => Response::HTTP_BAD_REQUEST,

        AccessDeniedHttpException::class   => Response::HTTP_UNAUTHORIZED,
        InvalidCredentialsException::class => Response::HTTP_UNAUTHORIZED,
        UnauthorizedHttpException::class   => Response::HTTP_UNAUTHORIZED,
        IdentityProviderException::class   => Response::HTTP_UNAUTHORIZED,

        CustomerNotFoundException::class                 => Response::HTTP_NOT_FOUND,
        CustomerByEmailAndDomainNotFoundException::class => Response::HTTP_NOT_FOUND,
        NotificationLogNotFoundException::class          => Response::HTTP_NOT_FOUND,
        SubscriberNotFoundException::class               => Response::HTTP_NOT_FOUND,

        CustomerAlreadyExistsException::class               => Response::HTTP_PRECONDITION_FAILED,
        CustomerCurrentPasswordDoesNotMatchException::class => Response::HTTP_PRECONDITION_FAILED,
        GeoPluginClientInvalidIpException::class            => Response::HTTP_PRECONDITION_FAILED,
        InvalidUuidException::class                         => Response::HTTP_PRECONDITION_FAILED,
        PreconditionFailedHttpException::class              => Response::HTTP_PRECONDITION_FAILED,
    ];
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $exceptionClass = $exception->getPrevious()
            ? get_class($exception->getPrevious())
            : get_class($exception);

        if ($this->isRequestValidation($exceptionClass)) {
            $response = new JsonResponse([
                'code'    => Response::HTTP_BAD_REQUEST,
                'message' => 'Invalid request data',
                'errors'  => $exception->getErrors(),
            ], Response::HTTP_BAD_REQUEST);

            $event->setResponse($response);

            $route = $event->getRequest()->get('_route', '');
            $this->logger->warning('Request Validation Errors', [
                'route'  => $route,
                'errors' => $exception->getErrors(),
            ]);

            return;
        }

        if ($this->hasException($exceptionClass)) {
            $response = new JsonResponse([
                'code'    => $this->statusCode($exceptionClass),
                'message' => $this->message($exception),
            ], $this->statusCode($exceptionClass));

            $event->setResponse($response);
        }

        $this->logger->error($exception->getMessage(), ['exception' => $exception]);
    }

    private function isRequestValidation(string $exceptionClass): bool
    {
        return HttpRequestValidationException::class === $exceptionClass;
    }

    private function hasException(string $exceptionClass): bool
    {
        return array_key_exists($exceptionClass, self::REGISTERED_EXCEPTIONS);
    }

    private function statusCode($exceptionClass): int
    {
        return self::REGISTERED_EXCEPTIONS[$exceptionClass];
    }

    private function message(Exception $exception): string
    {
        if ($exception instanceof InvalidArgumentException) {
            return 'Invalid argument';
        }

        return $exception->getMessage();
    }
}
