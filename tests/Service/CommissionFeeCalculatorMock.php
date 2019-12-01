<?php


namespace CommissionTask\Tests\Service;

use CommissionTask\Service\CommissionFeeCalculatorInterface;

class CommissionFeeCalculatorMock implements CommissionFeeCalculatorInterface
{
    private $index;

    public static function usortCallback($a, $b): int
    {
        //echo 'usortCallback() $a = '.print_r($a, true).PHP_EOL;
        $dateFieldIndex = 0;
        return $a['input'][$dateFieldIndex] <=> $b['input'][$dateFieldIndex];
    }

    public function getUsortCallback(): callable
    {
        return \Closure::fromCallable('self::usortCallback');
    }

    public function reset(): void
    {
        $this->index = 0;
    }

    public function calculate(array $input): float
    {
        return $this->index++;
    }
}
