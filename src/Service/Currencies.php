<?php

declare(strict_types=1);

namespace CommissionTask\Service;

/**
 * Class Currencies.
 *
 * Responsibilities:
 * store rates
 * support base operations
 */
class Currencies
{
    private $rates;

    public function __construct(array $rates)
    {
        $this->rates = $rates;
    }

    public function exchangeRate(string $cur1, string $cur2): float
    {
        $rate1 = $this->rates[$cur1];
        $rate2 = $this->rates[$cur2];

        return $rate1 / $rate2;
    }

    public function round(float $amount, string $currency): float
    {
        $precision = $currency === 'JPY' ? 0 : 2;
        $factor = $precision ? pow(10, $precision) : 1;
        $result = $precision ? $amount * $factor : $amount;
        if ($precision) {
            //Discard round errors
            $result = round($result, 6);
        }
        $result = ceil($result);
        $result = $precision ? $result / $factor : $result;

        return $result;
    }

    public function convert(float $amount, string $cur1, string $cur2, bool $ifRound = true): float
    {
        $result = $amount * $this->exchangeRate($cur2, $cur1);

        return $ifRound ? $this->round($result, $cur2) : $result;
    }
}
