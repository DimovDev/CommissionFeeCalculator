<?php

namespace CommissionFeeCalculator\Config;

final class CommissionConfig
{
    const DEFAULT_CURRENCY = 'EUR';
    const FREE_WEEK_WITHDRAW_COUNT = 3;
    const FREE_WEEK_WITHDRAW_AMOUNT = 1000;
    const DEPOSIT_RATE = 0.03;
    const PRIVATE_CLIENT_WITHDRAW_RATE = 0.3;
    const BUSINESS_CLIENT_WITHDRAW_RATE = 0.5;

    const TRANSACTION_DATA_KEYS = [
        'DATE',
        'USER_ID',
        'USER_TYPE',
        'OPERATION_TYPE',
        'OPERATION_AMOUNT',
        'CURRENCY'
    ];

    const ALLOWED_CURRENCIES = [
        'EUR',
        'USD',
        'JPY'
    ];

    const EXCHANGE_RATES_API_URL = 'http://api.exchangeratesapi.io/v1/latest';

    const API_KEY = '81d9c660fb09bc7a92f24dd6ebb16005';
}
