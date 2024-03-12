<?php

namespace CommissionFeeCalculator\Service\CommissionService;

use CommissionFeeCalculator\Config\CommissionConfig;
use CommissionFeeCalculator\Service\RateService\RateService;
use Exception;

class BusinessUserWithdrawCommissionFee implements CalculateCommissionFeeInterface
{

    protected RateService $rateService;
    private static float $businessClientWithdrawRate = CommissionConfig::BUSINESS_CLIENT_WITHDRAW_RATE;

    /**
     * Constructor for initializing RateService
     *
     * @param RateService $rateService The rate service to be initialized
     */
    public function __construct(RateService $rateService)
    {
        $this->rateService = $rateService;
    }


    /**
     * Calculate fee based on transaction data and business client withdraw rate.
     *
     * @param mixed $transactionData
     * @throws Exception description of exception
     * @return float
     */
    public function calculateFee($transactionData): float
    {
        return $this->rateService->getFeeByTransactionAmountAndRate($transactionData['OPERATION_AMOUNT'], self::$businessClientWithdrawRate);
    }
}