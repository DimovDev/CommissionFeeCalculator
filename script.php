<?php


use CommissionFeeCalculator\Controllers\CommissionController;
use DI\ContainerBuilder;
use DI\DependencyException;

require __DIR__ . DIRECTORY_SEPARATOR . "vendor/autoload.php";

if (count($argv) < 2) {
    throw new \Exception('Invalid number of parameters.');
}

$containerBuilder = new ContainerBuilder;
try {
    $container = $containerBuilder->build();
} catch (Exception $e) {
}
try {
    $fees = $container->get(CommissionController::class)->process($argv[1]);
} catch (DependencyException|Exception $e) {
}

// print commission fees
array_map(function ($fee) {
    echo PHP_EOL . number_format($fee, 2);
}, $fees);



