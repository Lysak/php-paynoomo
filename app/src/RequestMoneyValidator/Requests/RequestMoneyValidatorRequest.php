<?php

namespace App\src\RequestMoneyValidator\Requests;

use App\src\RequestMoneyValidator\Dto\RequestDto;
use Illuminate\Foundation\Http\FormRequest;

final class RequestMoneyValidatorRequest extends FormRequest
{
    public function toDto(): RequestDto
    {
        return RequestDto::fromRequest(
            $this->validate($this->rules())
        );
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/', 'gt:0'],
            'currency' => ['required', 'string'],
        ];
    }
}
