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

    protected $currencies;
    protected $users;

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

        }
        return 0;
    }

    protected function calculateCashInFee(array $input): float
    {
        $limit = $this->currencies->convert(
            self::CASH_IN_EUR_LIMIT, 'EUR', $input[self::CURRENCY_TYPE_INDEX]);
        $fee = $input[self::AMOUNT_TYPE_INDEX] * self::CASH_IN_FEE;
        $fee = $fee > $limit ? $limit : $fee;
        return $this->currencies->round($fee, $input[self::CURRENCY_TYPE_INDEX]);
    }

}