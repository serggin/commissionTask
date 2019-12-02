<?php
$errorMessage = 'First argument must be name of a valid csv file' . PHP_EOL;
if (count($argv) == 1) {
    exit($errorMessage);
}

$fname = $argv[1];
$f = fopen($fname, 'r');

$input = [];
while($line = fgetcsv($f)) {
    $input[] = $line;
}
fclose($f);
if(count($input) == 0) {
    exit($errorMessage);
}

require "vendor/autoload.php";

use CommissionTask\Service\CommissionFeeCalculator;
use CommissionTask\Service\CommissionManager;
use CommissionTask\Service\Currencies;

try {
    $rates = [
        'EUR' => 1,
        'USD' => 1.1497,
        'JPY' => 129.53
    ];
    $currencies = new Currencies($rates);
    $calculator = new CommissionFeeCalculator($currencies);
    $calculator->reset();
    $commissionManager = new CommissionManager($calculator);
    $result = $commissionManager->calculateCommission($input);
    foreach($result as $item) {
        echo $item. PHP_EOL;
    }
} catch (Exception $ex) {
    exit($ex->getMessage());
}
