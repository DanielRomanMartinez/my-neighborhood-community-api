<?php

declare(strict_types=1);

namespace App\Infrastructure\Template;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TwigExtension extends AbstractExtension
{
    private string $templateDir;

    public function __construct(string $templateDir)
    {
        $this->templateDir = $templateDir;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('asset_base64', [$this, 'assetBase64']),
        ];
    }

    public function assetBase64(string $file)
    {
        if (substr($file, 0, 1) != '/') {
            $file = '/' . $file;
        }

        $path = $this->templateDir . $file;

        if (!is_readable($path)) {
            return null;
        }

        return base64_encode(file_get_contents($path));
    }
}
