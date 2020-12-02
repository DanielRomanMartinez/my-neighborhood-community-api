<?php

namespace App\Infrastructure\Template;

use Twig\Environment;

class TwigTemplate implements TemplateInterface
{
    private Environment $templating;

    public function __construct(Environment $templating)
    {
        $this->templating = $templating;
    }

    public function render(string $template, array $parameters = []): string
    {
        try {
            return $this->templating->render($template, $parameters);
        } catch (\Throwable $error) {
            throw $error;
        }
    }
}
