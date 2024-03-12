<?php

namespace CommissionFeeCalculator\Service\CommissionService;

use CommissionFeeCalculator\Config\CommissionConfig;
use CommissionFeeCalculator\Service\RateService\RateService;
use Exception;

class CalculateDepositFee implements CalculateCommissionFeeInterface
{
    protected RateService $rateService;
    private static float $depositRate = CommissionConfig::DEPOSIT_RATE;

    /**
     * Constructor for the class.
     *
     * @param RateService $rateService
     */
    public function __construct(RateService $rateService)
    {
        $this->rateService = $rateService;
    }
    /**
     * Calculate the fee for a transaction based on the transaction data.
     *
     * @param array $transactionData The data of the transaction including the operation amount.
     * @return float The calculated transaction fee.
     *@throws Exception
     */
    public function calculateFee(array $transactionData): float
    {
        return $this->rateService->getFeeByTransactionAmountAndRate($transactionData['OPERATION_AMOUNT'], self::$depositRate);
    }
}