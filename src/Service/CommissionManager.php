<?php

declare(strict_types=1);

namespace CommissionTask\Service;

/**
 * Class CommissionManager.
 *
 * Responsibility:
 * Supports batch calculations
 */
class CommissionManager
{
    protected $inputData;
    protected $outputData;
    protected $calculator;

    public function __construct(CommissionFeeCalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }

    public function calculateCommission($input)
    {
        $this->inputData = $input;
        $this->outputData = [];
        $this->calculate();

        return $this->outputData;
    }

    protected function calculate(): void
    {
        $this->calculator->reset();
        $index = 0;
        foreach ($this->inputData as $input) {
            try {
                $this->outputData[] = $this->calculator->calculate($input);
            } catch (Exception $ex) {
                throw new Exception('CommissionManager calculate() failed on line '.$index.PHP_EOL.$ex->getMessage());
            }
        }
    }
}
