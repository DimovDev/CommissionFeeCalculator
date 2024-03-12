<?php

namespace CommissionFeeCalculator\Service\CommissionService;

use CommissionFeeCalculator\Config\CommissionConfig;
use Exception;
use RuntimeException;

class TransactionDataLoader
{

    private string $filePath;

    /**
     * Constructor for initializing the class
     *
     * @param string $filePath The file path to be initialized
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }


    /**
     * Get data from the specified file path.
     *
     * @throws Exception if the file path is invalid or the file couldn't be loaded
     * @return array the loaded transactions
     */
    public function getData(): array
    {
        if (!file_exists($this->filePath)) {
            throw new Exception($this->filePath . ' is not valid file path or file couldn\'t be loaded');
        }

        return $this->loadTransactions();
    }

    /**
     * Load transactions from a file and return them as an array.
     *
     * @return array
     * @throws RuntimeException Failed to open file
     */
    public function loadTransactions(): array
    {
        $columnCount = count(CommissionConfig::TRANSACTION_DATA_KEYS);
        $rows = [];
        if (($row = fopen($this->filePath, "r")) !== FALSE) {
            while (($transactionData = fgetcsv($row, null)) !== FALSE) {
                if(count($transactionData) !== $columnCount)
                {
                    echo "row does not have all the columns \n ->".json_encode($transactionData)."\n\n\n";
                    continue;
                }
                if (!in_array($transactionData[5], CommissionConfig::ALLOWED_CURRENCIES))
                {
                    echo "this currency is not allowed \n ->".$transactionData[5]." \n ->".json_encode($transactionData)."\n\n\n";

                    continue;
                }
                $rows[] = array_combine(CommissionConfig::TRANSACTION_DATA_KEYS, $transactionData);
            }
            fclose($row);
        }
        else
        {
            throw new RuntimeException('Failed to open file');
        }
        return $rows;
    }
}