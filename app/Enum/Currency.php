<?php

namespace App\Enum;

use InvalidArgumentException;

enum Currency: int
{
    case USD = 840;
    case EUR = 978;

    public static function get(string $currencyCode): self
    {
        $currencyCode = strtoupper($currencyCode);
        $currencyValues = self::cases();

        //TODO: find way to avoid foreach

        foreach ($currencyValues as $case) {
            if ($case->name === $currencyCode) {
                return $case;
            }
        }

        throw new InvalidArgumentException('Wrong currency');
    }

    public static function names(): array
    {
        $cases = self::cases();
        $names = [];

        foreach ($cases as $case) {
            $names[] = $case->name;
        }

        return $names;
    }
}
