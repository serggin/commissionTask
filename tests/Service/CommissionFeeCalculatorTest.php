<?php


namespace CommissionTask\Tests\Service;


use CommissionTask\Service\CommissionFeeCalculator;
use CommissionTask\Service\Currencies;
use PHPUnit\Framework\TestCase;

class CommissionFeeCalculatorTest extends TestCase
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


}