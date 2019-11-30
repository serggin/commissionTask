<?php

declare(strict_types=1);

namespace CommissionTask\Service;


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
        return $rate1/$rate2;
    }
}