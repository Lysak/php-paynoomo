<?php

namespace App\Enum;

use InvalidArgumentException;

enum Currency: int
{
    case USD = 840;
    case EUR = 978;

    public static function get(string $currencyName): self
    {
        $currencyName = strtoupper($currencyName);
        $currencyList = self::names();

        if (array_key_exists($currencyName, $currencyList)) {
            return $currencyList[$currencyName];
        }

        throw new InvalidArgumentException('Wrong currency');
    }

    public static function names(): array
    {
        $cases = self::cases();

        return array_reduce($cases, static function ($result, $case) {
            $result[$case->name] = $case;
            return $result;
        }, []);
    }
}
