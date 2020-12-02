<?php

declare(strict_types=1);

namespace App\Infrastructure\Pdf\Pdftk;

use App\Infrastructure\Pdf\Pdftk\Exception\PdftkCommandFailed;
use App\Infrastructure\Storage\LocalStorage;
use App\Shared\Domain\PdfGenerator\PdfMerger;

final class PdftkPdfMerger implements PdfMerger
{
    private LocalStorage $storage;

    public function __construct(LocalStorage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(array $files, string $outputFile): void
    {
        $aliases = [];
        $filesToDelete = [];

        foreach ($files as $file) {
            $filePath = sprintf('%s/%s', $this->storage->getDir($file['dir']), $file['filename']);

            if (file_exists($filePath)) {
                $aliases[] = sprintf('%s=%s', $file['alias'], $filePath);

                if (!empty($file['delete'])) {
                    $filesToDelete[] = $file['dir'] ?
                        sprintf('%s/%s', $file['dir'], $file['filename']) : $file['filename'];
                }
            }
        }

        $output = sprintf('%s/%s', $this->storage->getDir('temp'), $outputFile);

        $this->merge(implode(' ', $aliases), $output);

        foreach ($filesToDelete as $file) {
            $this->storage->removeFile($file);
        }
    }

    private function merge(string $aliases, string $output): void
    {
        $cmdOutput = $cmdExitCode = null;

        $commandToRun = sprintf('pdftk %s cat output %s', $aliases, $output);

        exec($commandToRun, $cmdOutput, $cmdExitCode);

        if (0 !== $cmdExitCode) {
            throw new PdftkCommandFailed(implode('; ', $cmdOutput));
        }
    }
}
