<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\File;

use App\Infrastructure\Storage\Exception\FileException;
use SplFileInfo;

final class File extends SplFileInfo implements FileInterface
{
    private string $mimeType;

    public function __construct(string $path)
    {
        if (!is_file($path)) {
            throw new FileException(sprintf('The file "%s" does not exist', $path));
        }

        $this->mimeType = mime_content_type($path);

        parent::__construct($path);
    }

    public function filename(): string
    {
        return parent::getFilename();
    }

    public function size(): int
    {
        return parent::getSize();
    }

    public function mimeType(): string
    {
        return $this->mimeType;
    }

    public function content(): ?string
    {
        if (!$this->isReadable()) {
            throw new FileException(sprintf('The content from file %s cannot be fetched.', $this->filename()));
        }

        $content = file_get_contents($this->getPathname());

        return $content ?? null;
    }

    public function toArray(): array
    {
        return [
            'filename'  => $this->filename(),
            'size'      => $this->size(),
            'mime_type' => $this->mimeType,
            'path'      => $this->getPathname(),
        ];
    }
}
