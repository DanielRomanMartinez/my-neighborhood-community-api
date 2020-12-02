<?php

declare(strict_types=1);

namespace App\Infrastructure\Template;

interface TemplateInterface
{
    public function render(string $template, array $parameters = []): string;
}
