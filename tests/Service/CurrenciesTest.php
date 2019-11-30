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
        $this->assertEquals($this->rates['EUR']/$this->rates['USD'], $this->currencies->exchangeRate('EUR', 'USD'));
    }
}