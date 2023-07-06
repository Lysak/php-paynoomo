<?php

namespace App\src\RequestMoneyValidator\Interfaces;

use App\src\RequestMoneyValidator\Dto\RequestDto;
use App\src\RequestMoneyValidator\Dto\TransactionDto;

interface RequestMoneyValidatorInterface
{
    public function validate(RequestDto $request, TransactionDto $transaction): bool;
}
