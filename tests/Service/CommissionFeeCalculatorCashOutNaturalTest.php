<?php


namespace CommissionTask\Tests\Service;


use CommissionTask\Service\CommissionFeeCalculator;
use CommissionTask\Service\Currencies;
use PHPUnit\Framework\TestCase;

class CommissionFeeCalculatorCashOutNaturalTest extends TestCase
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

    public function testEurCashOutNaturalDiscountFee(): void
    {
        foreach($this->eurCashOutNaturalDiscountFeeProvider() as $data) {
            $input = $data[0];
            $expected = $data[1];
            $fee = $this->calculator->calculate($input);
            $this->assertEquals($expected, $fee, $input[0]);
        }
    }

    public function testJpyCashOutNaturalDiscountFee(): void
    {
        foreach($this->jpyCashOutNaturalDiscountFeeProvider() as $data) {
            $input = $data[0];
            $expected = $data[1];
            $fee = $this->calculator->calculate($input);
            $this->assertEquals($expected, $fee, $input[0]);
        }
    }

    public function eurCashOutNaturalDiscountFeeProvider(): array
    {
        $arrayOfArrays = InputDataProvider::getInputData(5);
        $fee200 = round(200 * CommissionFeeCalculator::CASH_OUT_FEE, 2);
        return [
            [$arrayOfArrays[0], 0],
            [$arrayOfArrays[1], 0],
            [$arrayOfArrays[2], 0],
            [$arrayOfArrays[3], 0],
            [$arrayOfArrays[4], 0],
            [$arrayOfArrays[5], $fee200],
            [$arrayOfArrays[6], 0],
            [$arrayOfArrays[7], 0],
            [$arrayOfArrays[8], 0],
            [$arrayOfArrays[9], $fee200],
        ];
    }

    public function jpyCashOutNaturalDiscountFeeProvider(): array
    {
        $arrayOfArrays = InputDataProvider::getInputData(6);
        //$fee200 = round(200 * CommissionFeeCalculator::CASH_OUT_FEE, 2);
        return [
            [$arrayOfArrays[0], 8612],
        ];
    }
}