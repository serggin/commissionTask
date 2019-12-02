<?php


namespace CommissionTask\Tests\Service;


use CommissionTask\Service\CommissionFeeCalculator;
use CommissionTask\Service\Currencies;
use PHPUnit\Framework\TestCase;

class CommissionFeeCalculatorCashOutLegalTest extends TestCase
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

    public function testEurCashOutLegalFee(): void {
        $arrayOfArrays = InputDataProvider::getInputData(4);
        $eurLimit = CommissionFeeCalculator::CASH_OUT_LEGAL_EUR_LIMIT;

        $fee = $this->calculator->calculate($arrayOfArrays[0]);
        $this->assertTrue($fee == $eurLimit, 'EUR < .5EUR');
        $fee = $this->calculator->calculate($arrayOfArrays[1]);
        $this->assertTrue($fee == $eurLimit, 'EUR = .5EUR');
        $fee = $this->calculator->calculate($arrayOfArrays[2]);
        $this->assertTrue($fee > $eurLimit, 'EUR > .5EUR');
        $this->assertEquals(
            $fee,
            round($arrayOfArrays[2][4] * CommissionFeeCalculator::CASH_OUT_FEE, 2),
            'EUR > .5EUR EQ');
    }

}