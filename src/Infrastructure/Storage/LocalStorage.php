<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage;

use App\Infrastructure\Storage\Exception\FileException;
use App\Infrastructure\Storage\Exception\StorageException;
use App\Infrastructure\Storage\File\File;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class LocalStorage
{
    private Filesystem $filesystem;
    private string $rootStorageDir;

    public function __construct(Filesystem $filesystem, string $rootStorageDir)
    {
        $this->filesystem = $filesystem;
        $this->rootStorageDir = $rootStorageDir;

        if (!is_dir($rootStorageDir)) {
            throw new StorageException(sprintf('"%s" is not a valid directory.', $rootStorageDir));
        }
    }

    public function uploadContent(string $file, string $content): void
    {
        $path = $this->getAbsolutePath($file);

        try {
            $this->filesystem->dumpFile($path, $content);
        } catch (IOException $exception) {
            throw new StorageException(sprintf('Failed to create file "%s"', $path), $exception);
        }
    }

    public function removeFile(string $file): void
    {
        $fileObj = $this->getFile($file);

        try {
            $this->filesystem->remove($fileObj->getPathname());
        } catch (IOException $exception) {
            throw new StorageException(sprintf('Failed to remove file "%s"', $file), $exception);
        }
    }

    public function getFile(string $file): File
    {
        $path = $this->getAbsolutePath($file);

        try {
            return new File($path);
        } catch (FileException $exception) {
            throw new StorageException(sprintf('Failed to get file "%s"', $path), $exception);
        }
    }

    public function getDir(string $path = null): string
    {
        $dir = $this->rootStorageDir;

        if ($path) {
            $path = $this->trimPathSlashes($path);
            $dir .= '/' . $path;

            if (!is_dir($dir)) {
                throw new StorageException(sprintf('"%s" is not a valid directory.', $dir));
            }
        }

        return $dir;
    }

    private function getAbsolutePath(string $file): string
    {
        return $this->rootStorageDir . '/' . $file;
    }

    private function trimPathSlashes(string $path): string
    {
        $path = ltrim($path, '/');
        $path = rtrim($path, '/');

        return $path;
    }
}
