<?php


namespace CommissionTask\Service;


class CommissionFeeCalculator implements CommissionFeeCalculatorInterface
{
    protected const DATE_INDEX = 0;
    protected const UID_INDEX = 1;
    protected const USER_TYPE_INDEX = 2;
    protected const OPERATION_TYPE_INDEX = 3;
    protected const AMOUNT_TYPE_INDEX = 4;
    protected const CURRENCY_TYPE_INDEX = 5;

    protected const NATURAL_USER = 'natural';
    protected const LEGAL_USER = 'legal';

    protected const CASH_IN_TYPE = 'cash_in';
    protected const CASH_OUT_TYPE = 'cash_out';

    public const CASH_IN_EUR_LIMIT = 5.;
    public const CASH_IN_FEE = .0003;

    public const CASH_OUT_FEE = .003;
    public const CASH_OUT_NATURAL_EUR_LIMIT = 1000.;
    public const CASH_OUT_LEGAL_EUR_LIMIT = .5;
    public const CASH_OUT_NATURAL_OPERATION_LIMIT = 3;


    protected $currencies;
    protected $users;   //Natural users for cash out operations

    public function __construct(Currencies $currencies)
    {
        $this->currencies = $currencies;
    }

    public function reset(): void
    {
        $this->users = [];
    }

    public function calculate(array $input): float
    {
        if ($input[self::OPERATION_TYPE_INDEX] == self::CASH_IN_TYPE) {
            return $this->calculateCashInFee($input);
        } else {
            return $this->calculateCashOutFee($input);
        }
        return 0;
    }

    protected function calculateCashInFee(array $input): float
    {
        $limit = $this->currencies->convert(
            self::CASH_IN_EUR_LIMIT, 'EUR', $input[self::CURRENCY_TYPE_INDEX]);
        $fee = $input[self::AMOUNT_TYPE_INDEX] * self::CASH_IN_FEE;
        $fee = $this->currencies->round($fee, $input[self::CURRENCY_TYPE_INDEX]);
        $fee = $fee > $limit ? $limit : $fee;
        //return $this->currencies->round($fee, $input[self::CURRENCY_TYPE_INDEX]);
        return $fee;
    }

    protected function calculateCashOutFee(array $input): float
    {
        if ($input[self::USER_TYPE_INDEX] == self::LEGAL_USER) {
            return $this->calculateCashOutLegalFee($input);
        } else {
            return $this->calculateCashOutNaturalFee($input);
        }
        return 0;
    }

    protected function calculateCashOutLegalFee(array $input): float
    {
        $limit = $this->currencies->convert(
            self::CASH_OUT_LEGAL_EUR_LIMIT,
            'EUR',
            $input[self::CURRENCY_TYPE_INDEX]
        );
        $fee = $input[self::AMOUNT_TYPE_INDEX] * self::CASH_OUT_FEE;
        $fee = $this->currencies->round($fee, $input[self::CURRENCY_TYPE_INDEX]);
        $fee = $fee < $limit ? $limit : $fee;
        return $fee;
    }

    protected function calculateCashOutNaturalFee(array $input): float
    {
        $userId = $input[self::UID_INDEX];
        if (array_key_exists($userId, $this->users)) {
            $user = $this->users[$userId];
            $weekData = $user->getWeekData();
            $monday = self::mondayForDate($input[self::DATE_INDEX]);
            if ($weekData['date'] < $monday) {
                echo 'resetWeekData() $monday='.$monday.', '.$weekData['date'].PHP_EOL;
                $user->resetWeekData();
                $weekData = $user->getWeekData();
            }
        } else {
            $user = new CommissionFeeUser();

            $weekData = $user->getWeekData();
        }
        $weekCount = $weekData['count'];
        $weekAmount = $weekData['amount'];
        $amount = $input[self::AMOUNT_TYPE_INDEX];
        $eurAmount = $this->currencies->convert($amount, $input[self::CURRENCY_TYPE_INDEX], 'EUR');
        $user->addWeekData($input[self::DATE_INDEX], $eurAmount);
        $this->users[$userId] = $user;
        if ($weekCount < self::CASH_OUT_NATURAL_OPERATION_LIMIT) {
            $newWeekAmount = $weekAmount + $eurAmount;
            $amountExcess = $newWeekAmount - self::CASH_OUT_NATURAL_EUR_LIMIT;
            if ($input[self::DATE_INDEX] <= '2019-11-17') {
                //echo PHP_EOL.'$this->users='.print_r($this->users, true).PHP_EOL;
                //echo PHP_EOL.'$weekCount='.$weekCount.' $newWeekAmount='.$newWeekAmount.' $amountExcess='.$amountExcess.PHP_EOL;
            }
            if ($amountExcess <= 0) {
                $fee = 0;
            } else {
                $fee = $amountExcess * self::CASH_OUT_FEE;
            }
        } else {
            $fee = $eurAmount * self::CASH_OUT_FEE;
        }
        $fee = $this->currencies->convert($fee, 'EUR', $input[self::CURRENCY_TYPE_INDEX]);
        return $fee;
    }

    protected static function mondayForDate(string $date)
    {
        return date('Y-m-d', strtotime('last Monday', strtotime('+1 day', strtotime($date))));
    }
}
