<?php


namespace CommissionTask\Service;


interface CommissionFeeCalculatorInterface
{
    //public function getUsortCallback(): callable;
    public function reset(): void;
    public function calculate(array $input): string;
}