<?php

namespace Tests\Unit;

use App\src\Deviation\Deviation;
use App\src\RequestMoneyValidator\Dto\RequestDto;
use App\src\RequestMoneyValidator\Dto\TransactionDto;
use App\src\RequestMoneyValidator\Services\RequestMoneyValidator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class RequestMoneyValidatorTest extends TestCase
{
    //1. test case 1:
    //    - user requests "100 usd"
    //    - there is a transaction in a database with "90 USD"
    //    - the accepted deviation for all transactions is "10%" (that means that for this particular request the transaction is valid if it is between "90 usd" and "110 usd")
    //    - the result of validation is: TRUE - meaning that request matches the transaction, because the amount is in a range of accepted deviation (90 belongs to the range between 90 and 110)

    /**
     * php8.2 artisan test --filter=RequestMoneyValidatorTest::testDeviation10Request100USDTransaction90USDSuccess
     *
     * @return void
     */
    public function testDeviation10Request100USDTransaction90USDSuccess(): void
    {
        // Arrange
        $deviation = new Deviation(10);

        $validator = new RequestMoneyValidator($deviation);
        $requestDto = RequestDto::fromRequest(['amount' => 100, 'currency' => 'USD']);
        $transactionDto = TransactionDto::fromRequest(['amount' => 90, 'currency' => 'USD']);

        $requestDtoMock = m::mock($requestDto)->makePartial();
        $requestDtoMock->shouldReceive('getCurrency')->andReturn('USD');

        $transactionDtoMock = m::mock($transactionDto)->makePartial();
        $transactionDtoMock->shouldReceive('getCurrency')->andReturn('USD');

        // Act
        $result = $validator->validate($requestDto, $transactionDto);

        // Assert
        $this->assertTrue($result);
    }

    //2. test case 2:
    //    - user requests "100 usd"
    //    - there is a transaction in a database with "97.54 USD"
    //    - the accepted deviation for all transactions is "1%" (that means that for this particular request the transaction is valid if it is between "99 usd" and "101 usd")
    //    - the result of validation is: FALSE - meaning that request doesn't match the transaction, because the amount is not in a range of accepted deviation (97.54 does not belong to the range between 99 and 101)

    /**
     * php8.2 artisan test --filter=RequestMoneyValidatorTest::testDeviation1PercentRequest100USDTransaction97dot54USDFailure
     *
     * @return void
     */
    public function testDeviation1PercentRequest100USDTransaction97dot54USDFailure(): void
    {
        // Arrange
        $deviation = new Deviation(1);

        $validator = new RequestMoneyValidator($deviation);
        $requestDto = RequestDto::fromRequest(['amount' => 100, 'currency' => 'USD']);
        $transactionDto = TransactionDto::fromRequest(['amount' => 97.54, 'currency' => 'USD']);

        $requestDtoMock = m::mock($requestDto)->makePartial();
        $requestDtoMock->shouldReceive('getCurrency')->andReturn('USD');

        $transactionDtoMock = m::mock($transactionDto)->makePartial();
        $transactionDtoMock->shouldReceive('getCurrency')->andReturn('USD');

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The amount does not fall within a specified range of variation.');

        // Act
        $validator->validate($requestDto, $transactionDto);
    }

    // 3. test case 3:
    //    - user requests "100 usd"
    //    - there is a transaction is a database with "90 eur"
    //    - the result of validation is: FALSE - meaning that request doesn't match the transaction, because the currency is different (usd !== eur)

    /**
     * php8.2 artisan test --filter=RequestMoneyValidatorTest::testDeviation1PercentRequest100USDTransaction90EURFailure
     *
     * @return void
     */
    public function testDeviation1PercentRequest100USDTransaction90EURFailure(): void
    {
        // Arrange
        $deviation = new Deviation(1);

        $validator = new RequestMoneyValidator($deviation);
        $requestDto = RequestDto::fromRequest(['amount' => 100, 'currency' => 'USD']);
        $transactionDto = TransactionDto::fromRequest(['amount' => 90, 'currency' => 'EUR']);

        $requestDtoMock = m::mock($requestDto)->makePartial();
        $requestDtoMock->shouldReceive('getCurrency')->andReturn('USD');

        $transactionDtoMock = m::mock($transactionDto)->makePartial();
        $transactionDtoMock->shouldReceive('getCurrency')->andReturn('USD');

        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid currency');

        // Act
        $validator->validate($requestDto, $transactionDto);
    }

    protected function tearDown(): void
    {
        m::close();
    }
}
