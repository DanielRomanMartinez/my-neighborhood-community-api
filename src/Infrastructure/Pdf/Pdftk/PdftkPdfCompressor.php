<?php

declare(strict_types=1);

namespace App\Infrastructure\Pdf\Pdftk;

use App\Infrastructure\Pdf\Pdftk\Exception\PdftkCommandFailed;
use App\Infrastructure\Storage\LocalStorage;
use App\Shared\Domain\PdfGenerator\PdfCompressor;

final class PdftkPdfCompressor implements PdfCompressor
{
    private LocalStorage $storage;

    public function __construct(LocalStorage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(string $file, string $output, ?string $filePath = null, ?string $outputPath = null): void
    {
        $uncompressedFilePath = sprintf('%s/%s', $this->storage->getDir($filePath), $file);
        $compressedFilePath = sprintf('%s/%s', $this->storage->getDir($outputPath), $output);

        $this->compress($uncompressedFilePath, $compressedFilePath);

        $this->storage->removeFile($filePath ? sprintf('%s/%s', $filePath, $file) : $file);
    }

    private function compress(string $uncompressedFilePath, string $compressedFilePath): void
    {
        $cmdOutput = $cmdExitCode = null;

        $commandToRun = sprintf('pdftk %s output %s compress 2>&1', $uncompressedFilePath, $compressedFilePath);

        exec($commandToRun, $cmdOutput, $cmdExitCode);

        if (0 !== $cmdExitCode) {
            throw new PdftkCommandFailed(implode('; ', $cmdOutput));
        }
    }
}
