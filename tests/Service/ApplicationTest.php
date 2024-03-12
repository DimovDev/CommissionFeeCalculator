<?php

namespace CommissionFeeCalculator\Tests\Service;

use CommissionFeeCalculator\Controllers\CommissionController;
use DI\ContainerBuilder;
use Exception;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    private $controller;

    /**
     * Set up the function by initializing the container builder and getting the CommissionController instance.
     *
     * @return void
     * @throws Exception
     */
    public function setUp() : void
    {
        $containerBuilder = new ContainerBuilder;
        $container = $containerBuilder->build();
        $this->controller = $container->get(CommissionController::class);
    }

    /**
     * Test the process method of the CommissionController.
     * @throws Exception
     * @return void
     */
    public function testCommissionFees()
    {
        $fees = $this->controller->process('input.csv');

        $this->assertEquals([0.60, 3.00, 0.00, 0.06 ,1.50 ,0.00, 0.56, 0.30,0.30, 3.00, 0.00, 0.00, 8516.26], $fees);
    }

    }