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

use CommissionTask\Service\CommissionManager;

try {
    $commissionManager = new CommissionManager();
    $result = $commissionManager->calculateCommission($input);
} catch (Exception $ex) {
    exit($ex->getessage());
}
