<?php

namespace CommissionFeeCalculator\Service\CommissionService;

use CommissionFeeCalculator\Service\RateService\RateService;
use Exception;

class CalculateWithdrawFee implements CalculateCommissionFeeInterface
{

    private PrivateUserWithdrawCommissionFee $privateUserWithdrawCommissionFee;
    private BusinessUserWithdrawCommissionFee $businessUserWithdrawCommissionFee;

    /**
     * Constructor for initializing PrivateUserWithdrawCommissionFee and BusinessUserWithdrawCommissionFee.
     *
     * @param PrivateUserWithdrawCommissionFee $privateUserWithdrawCommissionFee The private user withdraw commission fee
     * @param BusinessUserWithdrawCommissionFee $businessUserWithdrawCommissionFee The business user withdraw commission fee
     */
    public function __construct(
        PrivateUserWithdrawCommissionFee  $privateUserWithdrawCommissionFee,
        BusinessUserWithdrawCommissionFee $businessUserWithdrawCommissionFee
    )
    {
        $this->businessUserWithdrawCommissionFee = $businessUserWithdrawCommissionFee;
        $this->privateUserWithdrawCommissionFee = $privateUserWithdrawCommissionFee;
    }

    /**
     * Calculate the fee based on the transaction data and user type.
     *
     * @param array $transactionData The transaction data
     * @return float The calculated fee
     * @throws Exception
     */
    public function calculateFee($transactionData): float
    {
        if($transactionData['USER_TYPE'] == "business")
        {
            return $this->businessUserWithdrawCommissionFee->calculateFee($transactionData);
        }
        else
        {
            return $this->privateUserWithdrawCommissionFee->calculateFee($transactionData);
        }
    }
}