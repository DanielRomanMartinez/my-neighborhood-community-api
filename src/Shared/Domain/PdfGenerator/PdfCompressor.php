<?php

declare(strict_types=1);

namespace App\Shared\Domain\PdfGenerator;

interface PdfCompressor
{
    public function __invoke(
        string $file,
        string $output,
        ?string $filePath = null,
        ?string $outputPath = null
    ): void;
}
