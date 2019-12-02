<?php


namespace CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use CommissionTask\Service\Currencies;
use CommissionTask\Service\CommissionFeeCalculator;
use CommissionTask\Service\CommissionManager;

class CommissionManagerTest extends TestCase
{
    private $input1 = [
        '2014-12-31,4,natural,cash_out,1200.00,EUR',
        '2015-01-01,4,natural,cash_out,1000.00,EUR',
        '2016-01-05,4,natural,cash_out,1000.00,EUR',
        '2016-01-05,1,natural,cash_in,200.00,EUR',
        '2016-01-06,2,legal,cash_out,300.00,EUR',
        '2016-01-06,1,natural,cash_out,30000,JPY',
        '2016-01-07,1,natural,cash_out,1000.00,EUR',
        '2016-01-07,1,natural,cash_out,100.00,USD',
        '2016-01-10,1,natural,cash_out,100.00,EUR',
        '2016-01-10,2,legal,cash_in,1000000.00,EUR',
        '2016-01-10,3,natural,cash_out,1000.00,EUR',
        '2016-02-15,1,natural,cash_out,300.00,EUR',
        '2016-02-19,5,natural,cash_out,3000000,JPY'
    ];
    private $output1 = [
        '0.60',
        '3.00',
        '0.00',
        '0.06',
        '0.90',
        '0',
        '0.70',
        '0.30',
        '0.30',
        '5.00',
        '0.00',
        '0.00',
        '8612'
    ];

    private $calculator;
    private $manager;

    public function setUp(): void
    {
        $rates = [
        'EUR' => 1,
        'USD' => 1.1497,
        'JPY' => 129.53
        ];
        $currencies = new Currencies($rates);
        $this->calculator = new CommissionFeeCalculator($currencies);
        $this->manager = new CommissionManager($this->calculator);
        $this->calculator->reset();
    }

    public function testResultSize(): void
    {
        $arrayOfArrays = $this->prepareInput($this->input1);
        $resultArray = $this->manager->calculateCommission($arrayOfArrays);
        $this->assertEquals(count($arrayOfArrays), count($resultArray));
    }

    public function testIntegralInput1(): void
    {
        $arrayOfArrays = $this->prepareInput($this->input1);
        $resultArray = $this->manager->calculateCommission($arrayOfArrays);
        for ($i=0; $i<count($this->output1); $i++) {
            $this->assertEquals($this->output1[$i], $resultArray[$i], 'record '.$i);
        }
    }

    private function prepareInput($input): array
    {
        return array_map(function($item) {
            return str_getcsv($item);
        }, $input);
    }
}