<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class UrlSlug extends Constraint
{
    public string $message = 'This value is not a valid url slug.';
}
