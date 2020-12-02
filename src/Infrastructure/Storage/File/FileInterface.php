<?php

namespace App\Infrastructure\Storage\File;

interface FileInterface
{
    public function filename(): string;

    public function content(): ?string;

    public function size(): int;

    public function mimeType(): string;

    public function toArray(): array;
}
