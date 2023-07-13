<?php

namespace App\src\RequestMoneyValidator\Services;

use App\src\Deviation\Deviation;
use App\src\RequestMoneyValidator\Dto\RequestDto;
use App\src\RequestMoneyValidator\Dto\TransactionDto;
use App\src\RequestMoneyValidator\Interfaces\RequestMoneyValidatorInterface;
use InvalidArgumentException;

final readonly class RequestMoneyValidator implements RequestMoneyValidatorInterface
{

    public function __construct(private Deviation $deviation)
    {
    }

    public function validate(RequestDto $request, TransactionDto $transaction): bool
    {
        if ($request->currency !== $transaction->currency) {
            throw new InvalidArgumentException('Invalid currency');
        }

        if (!$this->isInRangeOfAcceptedDeviation($request->convertAmountToCents(), $transaction->convertAmountToCents())) {
            throw new InvalidArgumentException('The amount does not fall within a specified range of variation.');
        }

        return true;
    }

    private function isInRangeOfAcceptedDeviation(int $requestAmountInCents, int $transactionAmountInCents): bool
    {
        // Calculate the minimum and maximum deviation based on the request amount and deviation percentage
        $minDeviation = (int) ($requestAmountInCents * (1 - ($this->deviation->percent / 100)));
        $maxDeviation = (int) ($requestAmountInCents * (1 + ($this->deviation->percent / 100)));

        // Check if the transaction amount falls within the accepted deviation range
        return $transactionAmountInCents >= $minDeviation && $transactionAmountInCents <= $maxDeviation;
    }

}
