<?php

namespace CommissionFeeCalculator\Service\RateService;

use CommissionFeeCalculator\Service\CurrencyRateService\ExchangeRatesApi;
use Exception;

class RateService
{

    private static $rates = [];

    /**
     * Constructor for the class.
     */
    public function __construct()
    {
        if(!count(self::$rates))
        {
            self::$rates = self::getExchangeRates();
        }
    }

    /**
     * Calculate fee based on transaction amount and rate.
     *
     * @param float $amount
     * @param float $rate
     * @return float
     */
    public function getFeeByTransactionAmountAndRate(float $amount, float $rate) : float
    {
        return (($rate * $amount) / 100.0);
    }

    /**
     * Convert the given amount to the user's currency.
     *
     * @param float $amount The amount to be converted
     * @param string $currency The currency to convert to
     * @return float The converted amount
     *@throws Exception Rates not found
     */
    public function ConvertToUserCurrency(float $amount, string $currency): float
    {
        if(empty(self::$rates))
        {
            throw new \Exception('Rates not found.');
        }

        return (float)($amount * self::$rates[$currency]);
    }

    /**
     * Convert the given amount from the specified currency to the default currency.
     *
     * @param float $amount The amount to be converted
     * @param string $currency The currency of the amount
     * @return float The converted amount in the default currency
     *@throws Exception Rates not found.
     */
    public function ConvertToDefaultCurrency(float $amount, string $currency): float
    {
        if(empty(self::$rates))
        {
            throw new \Exception('Rates not found.');
        }

        return (float)( $amount / self::$rates[$currency]);
    }

    /**
     * Get exchange rates from API.
     * @return array
     * @throws Exception
     */
    private static function getExchangeRates(): array
    {
        return (new ExchangeRatesApi())->fetchExchangeRates();
    }

}