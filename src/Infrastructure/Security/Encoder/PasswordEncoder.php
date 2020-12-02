<?php

declare(strict_types=1);

namespace App\Infrastructure\Security\Encoder;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface as SecurityPasswordEncoderInterface;

class PasswordEncoder implements PasswordEncoderInterface
{
    private const ENCODER_KEY = 'app_encoder';
    private SecurityPasswordEncoderInterface $encoder;

    public function __construct(
        EncoderFactoryInterface $encoderFactory
    ) {
        $this->encoder = $encoderFactory->getEncoder(self::ENCODER_KEY);
    }

    public function encode(string $plainPassword): string
    {
        return $this->encoder->encodePassword($plainPassword, null);
    }

    public function isValid(string $encodedPassword, string $plainPassword): bool
    {
        return $this->encoder->isPasswordValid($encodedPassword, $plainPassword, null);
    }
}
