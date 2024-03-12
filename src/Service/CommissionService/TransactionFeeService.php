<?php

namespace CommissionFeeCalculator\Service\CommissionService;

use CommissionFeeCalculator\Service\RateService\RateService;
use Exception;

class TransactionFeeService
{
    private CalculateWithdrawFee $calculateWithdrawFee;
    private CalculateDepositFee $calculateDepositFee;

    /**
     * Constructor for the class.
     *
     * @param CalculateWithdrawFee $calculateWithdrawFee
     * @param CalculateDepositFee $calculateDepositFee
     */
public function  __construct(CalculateWithdrawFee $calculateWithdrawFee, CalculateDepositFee $calculateDepositFee)
{
    $this->calculateWithdrawFee = $calculateWithdrawFee;
    $this->calculateDepositFee = $calculateDepositFee;
}

    /**
     * Calculate the fee for a given transaction data.
     *
     * @param array $transactionData The transaction data to calculate the fee for
     * @return float The calculated fee
     * @throws Exception
     */
    public function calculateFee(array $transactionData): float
    {
        $chargeFee = 0.00;

        if($transactionData['OPERATION_TYPE'] == "deposit")
        {
            $chargeFee = $this->calculateDepositFee->calculateFee($transactionData);
        }
        if($transactionData['OPERATION_TYPE'] == "withdraw")
        {
            $chargeFee =  $this->calculateWithdrawFee->calculateFee($transactionData);
        }

        return  number_format($chargeFee, 2, '.', '');
    }
}