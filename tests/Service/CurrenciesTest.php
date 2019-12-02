<?php
declare(strict_types=1);

namespace CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use CommissionTask\Service\Currencies;

class CurrenciesTest extends TestCase
{
    private $rates;
    private $currencies;

    public function setUp(): void {
        $this->rates = [
            'EUR' => 1,
            'USD' => 1.1497,
            'JPY' => 129.53
        ];
        $this->currencies = new Currencies($this->rates);
    }

    public function testUsdEur() {
        $this->assertEquals(
            $this->rates['USD'],
            $this->currencies->exchangeRate('USD', 'EUR'));
    }

/*    public function testEurUsd() {
        $this->assertEquals(
            round($this->rates['EUR']/$this->rates['USD'], 4),
            $this->currencies->exchangeRate('EUR', 'USD'));
    }*/

    public function testJpyEur() {
        $this->assertEquals(
            $this->rates['JPY'],
            $this->currencies->exchangeRate('JPY', 'EUR'));
    }

/*    public function testEurJpy() {
        $this->assertEquals(
            round($this->rates['EUR']/$this->rates['JPY'], 4),
            $this->currencies->exchangeRate('EUR', 'JPY'));
    }*/

/*    public function testJpyUsd() {
        $this->assertEquals(
            round($this->rates['JPY']/$this->rates['USD'], 2),
            $this->currencies->exchangeRate('JPY', 'USD'));
    }*/

/*    public function testUsdJpy() {
        $this->assertEquals(
            round($this->rates['USD']/$this->rates['JPY'], 4),
            $this->currencies->exchangeRate('USD', 'JPY'));
    }*/

    public function testConvertEurUsd() {
        $this->assertEquals(
            round($this->rates['USD'], 2),
            $this->currencies->convert(1.0,'EUR', 'USD'));
    }

    public function testConvertUsdEur() {
        $this->assertEquals(
            round(1./$this->rates['USD'], 2),
            $this->currencies->convert(1.0,'USD', 'EUR'));
    }

    public function testConvertUsdJpy() {
        $this->assertEquals(
            round($this->rates['JPY']/$this->rates['USD'], 0),
            $this->currencies->convert(1.0,'USD', 'JPY'));
    }

}