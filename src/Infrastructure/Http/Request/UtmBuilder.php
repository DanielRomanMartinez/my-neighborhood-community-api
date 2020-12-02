<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Request;

use App\Domain\Shared\Utm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class UtmBuilder
{
    private ?Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function __invoke(): Utm
    {
        $bodyContent = null !== $this->request ? json_decode($this->request->getContent(), true) : [];

        $source = $bodyContent['utm_source'] ?? null;
        $medium = $bodyContent['utm_medium'] ?? null;
        $campaign = $bodyContent['utm_campaign'] ?? null;

        return new Utm($source, $medium, $campaign);
    }
}
