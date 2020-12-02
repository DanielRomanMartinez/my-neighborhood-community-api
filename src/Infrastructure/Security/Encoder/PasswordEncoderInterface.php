<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Encoder;

interface PasswordEncoderInterface
{
    public function encode(string $plainPassword): string;

    public function isValid(string $encodedPassword, string $plainPassword): bool;
}
