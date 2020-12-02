<?php

declare(strict_types=1);

namespace App\Application\Customer\FindByEmail;

use App\Shared\Domain\Bus\Query\Response;
use App\Shared\Domain\ValueObject\ArrayValueObject;

final class FindByEmailResponse extends ArrayValueObject implements Response
{
}
