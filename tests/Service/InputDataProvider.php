<?php


namespace CommissionTask\Tests\Service;


class InputDataProvider
{
    private static $inputs = [
        0 => [
            '2014-12-31,4,natural,cash_out,1200.00,EUR',
            '2015-01-01,4,natural,cash_out,1000.00,EUR',
            '2016-01-05,4,natural,cash_out,1000.00,EUR',
            '2016-01-05,1,natural,cash_in,200.00,EUR',
            '2016-01-06,2,legal,cash_out,300.00,EUR',
            '2016-01-06,1,natural,cash_out,30000,JPY',
            '2016-01-07,1,natural,cash_out,1000.00,EUR',
            '2016-01-07,1,natural,cash_out,100.00,USD',
            '2016-01-10,1,natural,cash_out,100.00,EUR',
            '2016-01-10,2,legal,cash_in,1000000.00,EUR',
            '2016-01-10,3,natural,cash_out,1000.00,EUR',
            '2016-02-15,1,natural,cash_out,300.00,EUR',
            '2016-02-19,5,natural,cash_out,3000000,JPY'
        ],
        1 => [
            '2019-12-01,1,natural,cash_in,16666,EUR',
            '2019-12-02,1,natural,cash_in,16667,EUR',
            '2019-12-03,1,natural,cash_in,16668,EUR',
        ]
    ];

    public static function getInputData($index): array {
        return self::prepareInput(self::$inputs[$index]);
    }

    private static function prepareInput($input): array {
        return array_map(function($item) {
            return str_getcsv($item);
        }, $input);
    }
}
