<?php

namespace App\src\RequestMoneyValidator\Dto;

use App\Enum\Currency;

readonly final class TransactionDto
{
    private function __construct(
        public float $amount,
        public Currency $currency
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            amount: round($data['amount'], 2),
            currency: Currency::get($data['currency'])
        );
    }

    public function convertAmountToCents(): int
    {
        return $this->amount * 100;
    }
}
