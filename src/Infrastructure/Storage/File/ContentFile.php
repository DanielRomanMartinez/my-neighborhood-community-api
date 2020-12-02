<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage\File;

final class ContentFile implements FileInterface
{
    private string $fileName;
    private ?string $content;
    private int $size;
    private string $mimeType;

    public function __construct(
        string $fileName,
        ?string $content,
        int $size = null,
        string $mimeType = null
    ) {
        $this->fileName = $fileName;
        $this->content = $content;
        $this->size = $size ?? strlen($content);
        $this->mimeType = $mimeType ?? 'application/octet-stream';
    }

    public function filename(): string
    {
        return $this->fileName;
    }

    public function content(): ?string
    {
        return $this->content;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function mimeType(): string
    {
        return $this->mimeType;
    }

    public function toArray(): array
    {
        return [
            'filename'  => $this->fileName,
            'size'      => $this->size,
            'mime_type' => $this->mimeType,
            'content'   => $this->content ? base64_encode($this->content) : null,
        ];
    }
}
