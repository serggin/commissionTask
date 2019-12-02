<?php

declare(strict_types=1);

namespace CommissionTask\Service;

/**
 * Interface CommissionFeeCalculatorInterface.
 */
interface CommissionFeeCalculatorInterface
{
    public function reset(): void;

    public function calculate(array $input): string;
}
