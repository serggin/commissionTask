<?php


namespace CommissionTask\Service;


class CommissionManager
{
    protected $inputData;
    protected $calculator;

    public function __construct(CommissionFeeCalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }

    public function calculateCommission($input)
    {
        $this->inputData = [];
        $this->indexInput($input);
        $this->sortInput();
        $this->calculate();
        $this->unsortInput();
        return $this->prepareOutput();
    }

    protected function indexInput($input) : void
    {
        $index = 0;
        foreach ($input as $value) {
            $this->inputData[] = [
                'index' => $index++,
                'input' => $value,
                'output' => 0
            ];
        }
    }

    protected function sortInput() : void
    {
//        if (!usort($this->inputData, $this->calculator->getUsortCallback())) {
        if (!usort($this->inputData, $this->calculator->getUsortCallback())) {
            throw new Exception('CommissionManager usort() failed');
        };
    }

    protected function calculate() : void
    {
        $this->calculator->reset();
        foreach ($this->inputData as &$value) {
            try {
                $value['output'] = $this->calculator->calculate($value['input']);
            } catch  (Exception $ex) {
                throw new Exception(
                    'CommissionManager calculate() failed on line ' . $value['index'] . PHP_EOL .
                    $ex->getMessage()
                );
            }
        }
    }

    protected function unsortInput() : void
    {
        usort($this->inputData, function($a, $b) {
            $aIndex = $a['index'];
            $bIndex = $b['index'];
            return $aIndex < $bIndex ? -1 : 1;
        });
    }

    protected function prepareOutput() : array
    {
        return array_map(function($item) {
            return $item['output'];
        }, $this->inputData);
    }

}