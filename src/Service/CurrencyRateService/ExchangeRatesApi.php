<?php

namespace CommissionFeeCalculator\Service\CurrencyRateService;

use CommissionFeeCalculator\Config\CommissionConfig;
use Exception;

class ExchangeRatesApi
{
    private static string $endpoint = CommissionConfig::EXCHANGE_RATES_API_URL;
    private static string $accessKey = CommissionConfig::API_KEY;

    /**
     * Fetches the exchange rates from the specified endpoint using cURL.
     *
     * @throws Exception description of exception
     * @return array|null The exchange rates if successful, or null if an error occurs.
     */
    public function fetchExchangeRates(): ?array
    {
        try {
            $curl = curl_init(self::$endpoint . "?access_key=" .self::$accessKey);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($curl);

            curl_close($curl);

            $json = json_decode($result, true);
            if(isset($json["success"]) && $json["success"]) {
                return $json["rates"];
            }
            return null;
        }
        catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }

    }
}