<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\src\Deviation\Deviation;
use App\src\RequestMoneyValidator\Dto\TransactionDto;
use App\src\RequestMoneyValidator\Requests\RequestMoneyValidatorRequest;
use App\src\RequestMoneyValidator\Services\RequestMoneyValidator;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MoneyController extends Controller
{
    public function __construct(private readonly Deviation $deviation)
    {
    }

    final public function check(RequestMoneyValidatorRequest $request): JsonResponse
    {
        try {
            //TODO: find transaction
            // this is just for test
            $transaction = TransactionDto::fromRequest(
                [
                    'amount' => 80,
                    'currency' => 'usd',
                ]
            );

            $requestMoneyValidator = new RequestMoneyValidator($this->deviation);
            $isSuccessful = $requestMoneyValidator->validate(
                $request->toDto(),
                $transaction
            );

            if (!$isSuccessful) {
                throw new InvalidArgumentException('Request rejected');
            }

            return response()->json(
                [
                    'successful' => true,
                ],
                Response::HTTP_OK
            );
        } catch (Throwable $e) {
//            echo '<pre>';
//            print_r(__METHOD__ . ':' . __LINE__);
//            echo PHP_EOL;
//            echo PHP_EOL;
//            print_r([
//                        "message" => $e->getMessage(),
//                        "file" => $e->getFile(),
//                        "line" => $e->getLine(),
//                        "traceAsString" => $e->getTraceAsString()
//                    ]);
//            echo PHP_EOL;
//            echo PHP_EOL;
//            echo '</pre>';
//            die();
            return response()->json(
                [
                    'successful' => false,
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
