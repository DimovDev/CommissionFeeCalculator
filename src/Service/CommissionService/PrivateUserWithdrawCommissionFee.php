<?php

namespace CommissionFeeCalculator\Service\CommissionService;

use CommissionFeeCalculator\Config\CommissionConfig;
use CommissionFeeCalculator\Service\RateService\RateService;
use Exception;

class PrivateUserWithdrawCommissionFee implements CalculateCommissionFeeInterface
{

    private static array $privateWithdrawHistory = [];
    private static string $defaultCurrency = CommissionConfig::DEFAULT_CURRENCY;
    private static int $freeWeeklyWithdrawCount = CommissionConfig::FREE_WEEK_WITHDRAW_COUNT;
    private static float $privateClientWithdrawRate = CommissionConfig::PRIVATE_CLIENT_WITHDRAW_RATE;
    private static int $freeWeeklyWithdrawAmount = CommissionConfig::FREE_WEEK_WITHDRAW_AMOUNT;
    protected RateService $rateService;

    /**
     * Constructor for the class.
     * @param RateService $rateService
     */
    public function __construct( RateService $rateService)
    {
        $this->rateService =  $rateService;
    }

    /**
     * Calculate the fee for a transaction based on the transaction data.
     *
     * @param array $transactionData The data of the transaction including USER_ID, DATE, OPERATION_AMOUNT, and CURRENCY
     * @return float The calculated fee for the transaction
     */
    public function calculateFee(array $transactionData): float
    {
        $userId = $transactionData['USER_ID'];
        $date = $transactionData['DATE'];
        $withdrawAmount = (float) $transactionData['OPERATION_AMOUNT'];
        $originalAmount = $withdrawAmount;
        $currency = $transactionData['CURRENCY'];

        $lastMonday = self::getLastMonday($date);
        $this->initialiseUserPrivateHistory($userId, $lastMonday);

        if($currency != self::$defaultCurrency) {
            $withdrawAmount = $this->rateService->ConvertToDefaultCurrency($withdrawAmount, $currency);
        }

        $this->populateUserPrivateHistory($userId, $lastMonday, $withdrawAmount);
        $numberOfTransactions = $this->countWeeklyUserTransaction($userId, $lastMonday);
        $totalAmountOfTransaction = $this->sumWeeklyUserTransactionAmount($userId, $lastMonday);

        if($numberOfTransactions > self::$freeWeeklyWithdrawCount)
        {
            return $this->rateService->getFeeByTransactionAmountAndRate($originalAmount, self::$privateClientWithdrawRate);
        }

        return $this->getCommissionFee($originalAmount, $withdrawAmount, $totalAmountOfTransaction, $currency);
    }

    /**
     * Get the date of the last Monday before the given date.
     *
     * @param string $date The input date.
     * @return string The date of the last Monday.
     */
    private function getLastMonday(string $date): string
    {
        return date('Y-m-d', strtotime('previous monday', strtotime($date)));
    }

    /**
     * Initialise user's private history.
     *
     * @param int $userId
     * @param string $lastMonday
     */
    private function initialiseUserPrivateHistory(int $userId, string $lastMonday): void
    {
        if(!array_key_exists($userId, self::$privateWithdrawHistory)) {
            self::$privateWithdrawHistory[$userId] = [];
        }
        if(!array_key_exists($lastMonday, self::$privateWithdrawHistory[$userId])) {
            self::$privateWithdrawHistory[$userId][$lastMonday] = [];
        }
    }

    /**
     * A function to populate the private withdrawal history of a user.
     *
     * @param int $userId
     * @param string $lastMonday
     * @param float $withdrawAmount
     * @return void
     */
    private function populateUserPrivateHistory(int $userId, string $lastMonday, float $withdrawAmount): void
    {
        self::$privateWithdrawHistory[$userId][$lastMonday][] = $withdrawAmount;
    }

    /**
     * Counts the number of weekly user transactions.
     *
     * @param int $userId
     * @param string $lastMonday
     * @return int
     *@throws Exception
     */
    private function countWeeklyUserTransaction(int $userId, string $lastMonday): int
    {
        return count(self::$privateWithdrawHistory[$userId][$lastMonday]);
    }

    /**
     * Calculates the sum of the weekly user transaction amount.
     *
     * @param int $userId
     * @param string $lastMonday
     * @return float
     */
    private function sumWeeklyUserTransactionAmount(int $userId, string $lastMonday) : float
    {
        return (float) array_sum(self::$privateWithdrawHistory[$userId][$lastMonday]);
    }

    /**
     * Calculate the commission fee for a transaction.
     *
     * @param float $originalAmount
     * @param float $withdrawAmount
     * @param float $totalAmountOfTransaction
     * @param string $currency
     * @return float|0
     */
    private function getCommissionFee(float $originalAmount, float $withdrawAmount, float $totalAmountOfTransaction, string $currency)
    {
        if($totalAmountOfTransaction > self::$freeWeeklyWithdrawAmount) {
            if(($totalAmountOfTransaction - $withdrawAmount) > self::$freeWeeklyWithdrawAmount)
            {
                return $this->rateService->getFeeByTransactionAmountAndRate($originalAmount,  self::$privateClientWithdrawRate);
            }

            $remainingAmount = $totalAmountOfTransaction - self::$freeWeeklyWithdrawAmount;

            if($currency != self::$defaultCurrency)
            {
                $remainingAmount = $this->rateService->ConvertToUserCurrency($remainingAmount , $currency);
            }

            return $this->rateService->getFeeByTransactionAmountAndRate($remainingAmount,  self::$privateClientWithdrawRate);
        }

        return 0;
    }
}