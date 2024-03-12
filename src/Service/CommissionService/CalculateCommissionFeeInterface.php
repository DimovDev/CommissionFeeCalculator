<?php

namespace CommissionFeeCalculator\Service\CommissionService;

interface CalculateCommissionFeeInterface
{
    /**
     * Calculate the fee for the transaction data.
     *
     * @param array $transactionData
     * @return float
     */
    public function calculateFee(array $transactionData): float;
}