<?php

declare(strict_types=1);

namespace App\Shared\Domain\PdfGenerator;

interface PdfMerger
{
    public function __invoke(array $files, string $output): void;
}
