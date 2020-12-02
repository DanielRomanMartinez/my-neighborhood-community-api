<?php

declare(strict_types=1);

namespace App\UI\Controller\Front\Customer;

use App\Application\Customer\Create\CreateCustomerCommand;
use App\Application\Customer\Create\CreateCustomerCommandHandler;
use App\Infrastructure\Controller\BaseController;
use App\Infrastructure\Http\Request\HttpRequest;
use App\Shared\Domain\ValueObject\Uuid;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateCustomerPostController extends BaseController
{
    private CreateCustomerCommandHandler $handler;

    public function __construct(
        CreateCustomerCommandHandler $handler
    ) {
        $this->handler = $handler;
    }

    public function __invoke(HttpRequest $request): JsonResponse
    {
        $data = $request->getDataFromBodyContent();

        $customerId = Uuid::random()->value();
        $this->handler->__invoke(new CreateCustomerCommand(
            $customerId,
            $data['email'],
            $data['password'],
            $data['first_name'],
            $data['middle_name'],
            $data['last_name'],
            new DateTime(),
            new DateTime()
        ));

        return $this->json([], Response::HTTP_CREATED);
    }
}
