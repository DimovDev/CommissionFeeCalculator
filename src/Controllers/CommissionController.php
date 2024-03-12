<?php

namespace CommissionFeeCalculator\Controllers;

use CommissionFeeCalculator\Service\CommissionService\TransactionDataLoader;
use CommissionFeeCalculator\Service\CommissionService\TransactionFeeService;
use Exception;

class CommissionController
{
    private TransactionFeeService $transactionFeeService;

    /**
     * Constructor for the TransactionFeeService class.
     *
     * @param TransactionFeeService $transactionFeeService The transaction fee service
     */
    public function __construct(TransactionFeeService $transactionFeeService)
    {
        $this->transactionFeeService = $transactionFeeService;
    }

    /**
     * Get transaction data from input file
     *
     * @param string $fileName
     * @return array|null
     * @throws Exception
     */
    public function process(string $fileName): ?array
    {
        $transaction_data = new TransactionDataLoader($fileName);
        return $this->calculateFee($transaction_data);
    }

    /**
     * Calculate fee based on transaction data.
     *
     * @param TransactionDataLoader $transaction_data The transaction data loader object
     * @throws Exception description of exception
     * @return array The calculated fees
     */
    private function calculateFee(TransactionDataLoader $transaction_data): array
    {
        try {
           $fees = [];
            $fileRows = $transaction_data->getData();

            foreach ($fileRows as $row) {
                $fees[] = $this->transactionFeeService->calculateFee($row);
            }
            return $fees;

        }
        catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }
}