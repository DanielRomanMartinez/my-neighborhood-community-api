<?php

declare(strict_types=1);

namespace App\Shared\Domain\PdfGenerator;

interface PdfCreator
{
    public function __invoke(
        string $htmlContent,
        string $outputFile,
        ?string $marginLeft,
        ?string $marginRight,
        ?string $marginTop,
        ?string $marginBottom,
        bool $showPagination
    ): void;
}
