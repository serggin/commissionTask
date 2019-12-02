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
        //$precision = $cur2 === 'JPY' ? 2 : 4;
        //return round($rate1/$rate2, $precision);
        return $rate1/$rate2;
    }

    public function round(float $amount, string $currency): float
    {
        $precision = $currency === 'JPY' ? 0 : 2;
        $factor = $precision ? pow(10, $precision) : 1;
        $result = $precision ? $amount*$factor : $amount;
        if ($precision) {
            //Discard round errors
            $result = round($result, 6);
            //echo '$result='.$result.' ceil($result)='.ceil($result).PHP_EOL;
        }
        $result = ceil($result);
        $result = $precision ? $result/$factor : $result;
        //return round($result, $precision);
        return $result;
    }

    public function convert(float $amount, string $cur1, string $cur2, bool $ifRound=true): float
    {
        $result = $amount * $this->exchangeRate($cur2, $cur1);
        return $ifRound ? $this->round($result, $cur2) : $result;
    }
}
