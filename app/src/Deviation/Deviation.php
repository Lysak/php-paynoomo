<?php

namespace App\src\Deviation;

use InvalidArgumentException;

final readonly class Deviation
{
    public function __construct(public float $percent) // DTO: validate percent
    {
        if (!preg_match('/^\d+(\.\d{1,2})?$/', (string)$percent)) {
            throw new InvalidArgumentException('Invalid percent value. The percent value must be a float with up to two digits after the decimal point.');
        }

        if ($percent < 0 || $percent > 100) {
            throw new InvalidArgumentException('Invalid percent value. The percent value must be between 0 and 100.');
        }
    }
}
