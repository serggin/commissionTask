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

    public function testEurCashInFee(): void {
        $arrayOfArrays = InputDataProvider::getInputData(1);
        $eurLimit = CommissionFeeCalculator::CASH_IN_EUR_LIMIT;

        $fee = $this->calculator->calculate($arrayOfArrays[0]);
        //echo '<5EUR in fee = '.$fee.PHP_EOL;
        $this->assertTrue($fee < $eurLimit, '< 5EUR');
        $fee = $this->calculator->calculate($arrayOfArrays[1]);
        //echo '5EUR in fee = '.$fee.PHP_EOL;
        $this->assertTrue($fee == $eurLimit, '= 5EUR');
        $fee = $this->calculator->calculate($arrayOfArrays[2]);
        $this->assertTrue($fee == $eurLimit, '> 5EUR');
        //$resultArray = $this->manager->calculateCommission($arrayOfArrays);

    }
}