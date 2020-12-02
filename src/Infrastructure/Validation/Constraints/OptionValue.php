<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

final class OptionValue extends Constraint
{
    const INVALID_OPTION_ERROR = '2e6bcfaf-0d4d-43fe-b7f7-cbe9e179e572';

    protected static $errorNames = [
        self::INVALID_OPTION_ERROR => 'INVALID_OPTION_ERROR',
    ];

    public string $message = 'This value is not a valid option.';

    public $callback;

    public function __construct($options = null)
    {
        if (!isset($options['callback'])) {
            throw new MissingOptionsException(sprintf('Option "callback" is required for constraint %s', __CLASS__), ['callback']);
        }

        $this->callback = $options['callback'];
        unset($options['callback']);

        parent::__construct($options);
    }
}
