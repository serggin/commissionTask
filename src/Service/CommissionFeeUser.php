<?php

declare(strict_types=1);

namespace CommissionTask\Service;

/**
 * Class CommissionFeeUser.
 *
 * Responsibily:
 * store and handle data for natural user
 */
class CommissionFeeUser
{
    private $date;
    private $weekCount;
    private $weekAmount;

    public function __construct()
    {
        $this->resetWeekData();
    }

    public function resetWeekData()
    {
        $this->date = null;
        $this->weekCount = 0;
        $this->weekAmount = 0;
    }

    public function getWeekData(): array
    {
        return [
            'date' => $this->date,
            'count' => $this->weekCount,
            'amount' => $this->weekAmount,
        ];
    }

    public function addWeekData(string $date, float $eurAmount): void
    {
        $this->date = $date;
        ++$this->weekCount;
        $this->weekAmount += $eurAmount;
    }
}
