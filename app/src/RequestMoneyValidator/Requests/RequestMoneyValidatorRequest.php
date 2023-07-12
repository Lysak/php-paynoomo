<?php

namespace App\src\RequestMoneyValidator\Requests;

use App\Enum\Currency;
use App\src\RequestMoneyValidator\Dto\RequestDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'currency' => [
                'required',
                'string',
                static function ($attribute, $value, $fail) {
                    $value = strtoupper($value);
                    $validCurrencies = array_column(Currency::cases(), 'name');
                    if (!in_array($value, $validCurrencies)) {
                        $fail('The ' . $attribute . ' field is invalid.');
                    }
                }
            ],
        ];
    }
}
