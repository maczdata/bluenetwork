<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           FixerExchange.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services\Exchange;

use App\Repositories\Common\CurrencyRepository;
use App\Repositories\Common\ExchangeRateRepository;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class FixerExchange
 * @package App\Services\Exchange
 */
class FixerExchange extends ExchangeRate
{
    /**
     * API key
     *
     * @var string
     */
    protected mixed $apiKey;

    /**
     * API endpoint
     *
     * @var string
     */
    protected string $apiEndPoint;

    /**
     * Create a new helper instance.
     *
     * @param CurrencyRepository $currencyRepository
     * @param ExchangeRateRepository $exchangeRateRepository
     */
    public function __construct(
        protected CurrencyRepository $currencyRepository,
        protected ExchangeRateRepository $exchangeRateRepository
    ) {
        $this->apiEndPoint = 'https://data.fixer.io/api';

        $this->apiKey = config('services.exchange-api')['fixer']['key'];
    }

    /**
     * Fetch rates and updates in currency_exchange_rates table
     *
     * @throws GuzzleException
     * @throws ValidatorException
     * @throws Exception
     */
    public function updateRates()
    {
        $client = new \GuzzleHttp\Client();

        foreach ($this->currencyRepository->all() as $currency) {
            if ($currency->code == config('app.currency')) {
                continue;
            }

            //$result = $client->request('GET', $this->apiEndPoint . '/' . date('Y-m-d') . '?access_key=' . $this->apiKey .'&base=' . config('app.currency') . '&symbols=' . $currency->code);

            $result = $client->request('GET', $this->apiEndPoint . '/latest' . '?access_key=' . $this->apiKey . '&symbols=' . $currency->code);

            $result = json_decode($result->getBody()->getContents(), true);

            if (isset($result['success']) && !$result['success']) {
                throw new Exception(
                    $result['error']['info'] ?? $result['error']['type'],
                    1
                );
            }

            if ($exchangeRate = $currency->exchange_rate) {
                $this->exchangeRateRepository->update([
                    'rate' => $result['rates'][$currency->code],
                ], $exchangeRate->id);
            } else {
                $this->exchangeRateRepository->create([
                    'rate' => $result['rates'][$currency->code],
                    'target_currency' => $currency->id,
                ]);
            }
        }
    }
}
