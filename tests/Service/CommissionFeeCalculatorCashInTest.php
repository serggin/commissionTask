<?php


namespace CommissionTask\Tests\Service;


use CommissionTask\Service\CommissionFeeCalculator;
use CommissionTask\Service\Currencies;
use PHPUnit\Framework\TestCase;

class CommissionFeeCalculatorCashInTest extends TestCase
{
    private $rates;
    private $currencies;
    private $calculator;

    public function setUp(): void {
        $this->rates = [
            'EUR' => 1,
            'USD' => 1.1497,
            'JPY' => 129.53
        ];
        $this->currencies = new Currencies($this->rates);
        $this->calculator = new CommissionFeeCalculator($this->currencies);
        $this->calculator->reset();
    }

    public function testEurCashInFee(): void {
        $arrayOfArrays = InputDataProvider::getInputData(1);
        $eurLimit = CommissionFeeCalculator::CASH_IN_EUR_LIMIT;

        $fee = $this->calculator->calculate($arrayOfArrays[0]);
        $this->assertTrue($fee < $eurLimit, 'EUR < 5EUR');
        $fee = $this->calculator->calculate($arrayOfArrays[1]);
        $this->assertTrue($fee == $eurLimit, 'EUR = 5EUR');
        $fee = $this->calculator->calculate($arrayOfArrays[2]);
        $this->assertTrue($fee == $eurLimit, 'EUR > 5EUR');
    }

    public function testUsdCashInFee(): void
    {
        $arrayOfArrays = InputDataProvider::getInputData(2);
        $usdLimit = $this->currencies->convert(
            CommissionFeeCalculator::CASH_IN_EUR_LIMIT,
            'EUR',
            'USD'
        );
        $fee = $this->calculator->calculate($arrayOfArrays[0]);
        $this->assertTrue($fee < $usdLimit, 'USD < 5EUR');
        $fee = $this->calculator->calculate($arrayOfArrays[1]);
        $this->assertTrue($fee == $usdLimit, 'USD = 5EUR');
        $fee = $this->calculator->calculate($arrayOfArrays[2]);
        $this->assertTrue($fee == $usdLimit, 'USD > 5EUR');
    }

    public function testJpyCashInFee(): void
    {
        $arrayOfArrays = InputDataProvider::getInputData(3);
        $jpyLimit = $this->currencies->convert(
            CommissionFeeCalculator::CASH_IN_EUR_LIMIT,
            'EUR',
            'JPY'
        );
        $fee = $this->calculator->calculate($arrayOfArrays[0]);
        $this->assertTrue($fee < $jpyLimit, 'JPY < 5EUR');
        $fee = $this->calculator->calculate($arrayOfArrays[1]);
        $this->assertTrue($fee == $jpyLimit, 'JPY = 5EUR');
        $fee = $this->calculator->calculate($arrayOfArrays[2]);
        $this->assertTrue($fee == $jpyLimit, 'JPY > 5EUR');
    }
}