<?php

declare(strict_types=1);

namespace App\UI\Controller\Front\Customer;

use App\Application\Customer\FindByEmail\FindByEmailQuery;
use App\Application\Customer\FindByEmail\FindByEmailQueryHandler;
use App\Infrastructure\Controller\BaseController;
use App\Infrastructure\Http\Request\HttpRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FindCustomersGetController extends BaseController
{
    private FindByEmailQueryHandler $findByEmailQueryHandler;

    public function __construct(
        FindByEmailQueryHandler $findByEmailQueryHandler
    ) {
        $this->findByEmailQueryHandler = $findByEmailQueryHandler;
    }

    public function __invoke(HttpRequest $request): JsonResponse
    {
        $params = $request->getRequest()->query->all();

        $customerResponse = $this->findByEmailQueryHandler->__invoke(
            new FindByEmailQuery($params['email'])
        );

        return $this->json([
            'customer' => $customerResponse->value(),
        ]);
    }
}
