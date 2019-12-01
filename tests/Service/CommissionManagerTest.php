<?php


namespace CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
//use CommissionFeeCalculatorMock;
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

    private $calculator;
    private $manager;

    public function setUp(): void {
        $this->calculator = new CommissionFeeCalculatorMock();  // ?? class prop is needed?
        $this->manager = new CommissionManager($this->calculator);
    }

    public function testResultSize(): void
    {
        $arrayOfArrays = $this->prepareInput($this->input1);
        $resultArray = $this->manager->calculateCommission($arrayOfArrays);
        $this->assertEquals(count($arrayOfArrays), count($resultArray));
    }

/*    public function _testSorting(): void
    {
        $dateFieldIndex = 0;
        $arrayOfArrays = $this->prepareInput($this->input1);
        $resultArray = $this->manager->calculateCommission($arrayOfArrays);
        echo 'testSorting() $resultArray = '.print_r($resultArray, true).PHP_EOL;
        foreach ($resultArray as $output) {

        }
    }*/

    private function prepareInput($input): array {
        return array_map(function($item) {
            return str_getcsv($item);
        }, $input);
    }
}