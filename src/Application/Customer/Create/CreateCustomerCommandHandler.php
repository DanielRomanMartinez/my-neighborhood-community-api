<?php

declare(strict_types=1);

namespace App\Application\Customer\Create;

use App\Infrastructure\Security\Encoder\PasswordEncoderInterface;

final class CreateCustomerCommandHandler
{
    private PasswordEncoderInterface $passwordEncoder;
    private CreateCustomerUseCase $useCase;

    public function __construct(
        PasswordEncoderInterface $passwordEncoder,
        CreateCustomerUseCase $useCase
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->useCase = $useCase;
    }

    public function __invoke(CreateCustomerCommand $command): void
    {
        $this->useCase->__invoke(
            $command->id(),
            $command->email(),
            $this->passwordEncoder->encode($command->password()),
            $command->firstName(),
            $command->middleName(),
            $command->lastName(),
            $command->createdAt(),
            $command->updatedAt()
        );
    }
}
