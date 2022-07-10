<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           Core.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services;


use App\Repositories\Common\CurrencyRepository;
use App\Repositories\Common\ExchangeRateRepository;

use DateTime;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HigherOrderCollectionProxy;

class Core
{

    /**
     * @var CurrencyRepository
     */
    protected CurrencyRepository $currencyRepository;

    /**
     * @var ExchangeRateRepository
     */
    protected ExchangeRateRepository $exchangeRateRepository;


    public function __construct(
        //LocaleRepository $localeRepository,
        //CoreConfigRepository $coreConfigRepository,
        CurrencyRepository $currencyRepository,
        ExchangeRateRepository $exchangeRateRepository
    )
    {

        //$this->localeRepository = $localeRepository;

        //$this->coreConfigRepository = $coreConfigRepository;

        $this->currencyRepository = $currencyRepository;

        $this->exchangeRateRepository = $exchangeRateRepository;
    }

    /**
     * Returns all currencies
     *
     * @return Collection
     */
    public function getAllCurrencies()
    {
        static $currencies;

        if ($currencies) {
            return $currencies;
        }

        return $currencies = $this->currencyRepository->all();
    }

    /**
     * Returns base channel's currency model
     *
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function getBaseCurrency(): mixed
    {
        static $currency;

        if ($currency) {
            return $currency;
        }

        $baseCurrency = $this->currencyRepository->findOneByField('code', config('app.currency'));

        if (!$baseCurrency) {
            $baseCurrency = $this->currencyRepository->first();
        }

        return $currency = $baseCurrency;
    }


    /**
     * Returns base channel's currency code
     *
     * @return string
     */
    public function getBaseCurrencyCode(): string
    {
        static $currencyCode;

        if ($currencyCode) {
            return $currencyCode;
        }

        return ($currency = $this->getBaseCurrency()) ? $currencyCode = $currency->code : '';
    }

    /**
     * @return mixed|null
     */
    public function getCurrentCurrency()
    {
        static $currency;

        if ($currency) {
            return $currency;
        }

        if ($currencyCode = session()->get('currency')) {
            if ($currency = $this->currencyRepository->findOneByField('code', $currencyCode)) {
                return $currency;
            }
        }

        return $currency = $this->getBaseCurrency();
    }

    /**
     * @return mixed
     */
    public function getCurrentCurrencyCode(): mixed
    {
        static $currencyCode;

        if ($currencyCode) {
            return $currencyCode;
        }

        return ($currency = $this->getCurrentCurrency()) ? $currencyCode = $currency->code : '';
    }


    /**
     * Converts price
     *
     * @param float $amount
     * @param string $targetCurrencyCode
     * @param string $orderCurrencyCode
     *
     * @return string
     */
    public function convertPrice($amount, $targetCurrencyCode = null, $orderCurrencyCode = null)
    {
        if (!isset($this->lastCurrencyCode)) {
            $this->lastCurrencyCode = $this->getBaseCurrency()->code;
        }

        if ($orderCurrencyCode) {
            if (!isset($this->lastOrderCode)) {
                $this->lastOrderCode = $orderCurrencyCode;
            }

            if (($targetCurrencyCode != $this->lastOrderCode)
                && ($targetCurrencyCode != $orderCurrencyCode)
                && ($orderCurrencyCode != $this->getBaseCurrencyCode())
                && ($orderCurrencyCode != $this->lastCurrencyCode)
            ) {
                $amount = $this->convertToBasePrice($amount, $orderCurrencyCode);
            }
        }

        $targetCurrency = !$targetCurrencyCode
            ? $this->getCurrentCurrency()
            : $this->currencyRepository->findOneByField('code', $targetCurrencyCode);

        if (!$targetCurrency) {
            return $amount;
        }

        $exchangeRate = $this->exchangeRateRepository->findOneWhere([
            'target_currency' => $targetCurrency->id,
        ]);

        if (null === $exchangeRate || !$exchangeRate->rate) {
            return $amount;
        }

        $result = (float)$amount * (float)($this->lastCurrencyCode == $targetCurrency->code ? 1.0 : $exchangeRate->rate);

        if ($this->lastCurrencyCode != $targetCurrency->code) {
            $this->lastCurrencyCode = $targetCurrency->code;
        }

        return $result;
    }

    /**
     * Converts to base price
     *
     * @param float $amount
     * @param null $targetCurrencyCode
     *
     * @return string
     */
    public function convertToBasePrice(float $amount, $targetCurrencyCode = null)
    {
        $targetCurrency = !$targetCurrencyCode
            ? $this->getCurrentCurrency()
            : $this->currencyRepository->findOneByField('code', $targetCurrencyCode);

        if (!$targetCurrency) {
            return $amount;
        }

        $exchangeRate = $this->exchangeRateRepository->findOneWhere([
            'target_currency' => $targetCurrency->id,
        ]);

        if (null === $exchangeRate || !$exchangeRate->rate) {
            return $amount;
        }

        return (float)$amount / $exchangeRate->rate;
    }

    /**
     * Format and convert price with currency symbol
     *
     * @param int $amount
     * @return string
     */
    public function currency($amount = 0)
    {
        if (is_null($amount)) {
            $amount = 0;
        }

        return $this->formatPrice($this->convertPrice($amount), $this->getCurrentCurrency()->code);
    }

    /**
     * Return currency symbol from currency code
     *
     * @param $code
     * @return string
     */
    public function currencySymbol($code)
    {
        $formatter = new \NumberFormatter(app()->getLocale() . '@currency=' . $code, \NumberFormatter::CURRENCY);

        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }

    /**
     * Format and convert price with currency symbol
     *
     * @param float $price
     *
     * @param string $currencyCode
     * @return string
     */

    public function formatPrice(float $price, string $currencyCode): string
    {
        if (is_null($price))
            $price = 0;

        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($price, $currencyCode);
    }

    /**
     * Format and convert price with currency symbol
     *
     * @return array
     */
    public function getAccountJsSymbols()
    {
        $formater = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        $pattern = $formater->getPattern();

        $pattern = str_replace("¤", "%s", $pattern);

        $pattern = str_replace("#,##0.00", "%v", $pattern);

        return [
            'symbol' => core()->currencySymbol(core()->getCurrentCurrencyCode()),
            'decimal' => $formater->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL),
            'format' => $pattern,
        ];
    }

    /**
     * Format price with base currency symbol
     *
     * @param float $price
     *
     * @return string
     */
    public function formatBasePrice(float $price = 0.00)
    {

        $formater = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        if ($symbol = $this->getBaseCurrency()->symbol) {
            if ($this->currencySymbol($this->getBaseCurrencyCode()) == $symbol) {
                return $formater->formatCurrency($price, $this->getBaseCurrencyCode());
            } else {
                $formater->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $symbol);

                return $formater->format($this->convertPrice($price));
            }
        } else {
            return $formater->formatCurrency($price, $this->getBaseCurrencyCode());
        }
    }

    /**
     * Check whether sql date is empty
     *
     * @param string $date
     *
     * @return bool
     */
    function is_empty_date($date)
    {
        return preg_replace('#[ 0:-]#', '', $date) === '';
    }

    /**
     * Format date using current channel.
     *
     * @param Carbon|null $date
     * @param string $format
     *
     * @return  string
     */
    public function formatDate($date = null, $format = 'd-m-Y H:i:s')
    {
        if (is_null($date)) {
            $date = Carbon::now();
        }

        return $date->format($format);
    }

    /**
     * Retrieve information from payment configuration
     *
     * @param string $field
     * @param string|null $locale
     *
     * @return mixed
     */
    public function getConfigData($field, $locale = null)
    {

        if (null === $locale) {
            $locale = request()->get('locale') ?: app()->getLocale();
        }

        $fields = $this->getConfigField($field);

        $locale_based = false;
        if (isset($fields['locale_based']) && $fields['locale_based']) {
            $locale_based = true;
        }
        if (isset($fields['locale_based']) && $fields['locale_based']) {
            $coreConfigValue = $this->coreConfigRepository->findOneWhere([
                'code' => $field,
                'locale_code' => $locale,
            ]);
        } else {
            $coreConfigValue = $this->coreConfigRepository->findOneWhere([
                'code' => $field,
            ]);
        }

        if (!$coreConfigValue) {
            $fields = explode(".", $field);

            array_shift($fields);

            $field = implode(".", $fields);

            return Config::get($field);
        }

        return $coreConfigValue->value;
    }

    /**
     * Retrieve a group of information from the core config table
     *
     * @param mixed $criteria
     *
     * @return mixed
     */
    public function retrieveGroupConfig($criteria)
    {
        return $criteria;
    }

    /**
     * Checks if current date of the given channel (in the channel timezone) is within the range
     *
     * @param string|null $dateFrom
     * @param string|null $dateTo
     *
     * @return bool
     */
    public function DateInInterval($dateFrom = null, $dateTo = null)
    {
        $currentTimeStamp = strtotime(date('Y-m-d H:i:s'));

        $fromTimeStamp = strtotime($dateFrom);

        $toTimeStamp = strtotime($dateTo);

        if ($dateTo) {
            $toTimeStamp += 86400;
        }

        if (!$this->is_empty_date($dateFrom) && $currentTimeStamp < $fromTimeStamp) {
            $result = false;
        } elseif (!$this->is_empty_date($dateTo) && $currentTimeStamp > $toTimeStamp) {
            $result = false;
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * Returns time intervals
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     *
     * @return array
     */
    public function getTimeInterval(Carbon $startDate, Carbon $endDate)
    {
        $timeIntervals = [];

        $totalDays = $startDate->diffInDays($endDate) + 1;
        $totalMonths = $startDate->diffInMonths($endDate) + 1;

        $startWeekDay = Carbon::createFromTimeString($this->xWeekRange($startDate, 0) . ' 00:00:01');
        $endWeekDay = Carbon::createFromTimeString($this->xWeekRange($endDate, 1) . ' 23:59:59');
        $totalWeeks = $startWeekDay->diffInWeeks($endWeekDay);

        if ($totalMonths > 5) {
            for ($i = 0; $i < $totalMonths; $i++) {
                $date = clone $startDate;
                $date->addMonths($i);

                $start = Carbon::createFromTimeString($date->format('Y-m-d') . ' 00:00:01');
                $end = $totalMonths - 1 == $i
                    ? $endDate
                    : Carbon::createFromTimeString($date->format('Y-m-d') . ' 23:59:59');

                $timeIntervals[] = ['start' => $start, 'end' => $end, 'formatedDate' => $date->format('M')];
            }
        } elseif ($totalWeeks > 6) {
            for ($i = 0; $i < $totalWeeks; $i++) {
                $date = clone $startDate;
                $date->addWeeks($i);

                $start = $i == 0
                    ? $startDate
                    : Carbon::createFromTimeString($this->xWeekRange($date, 0) . ' 00:00:01');
                $end = $totalWeeks - 1 == $i
                    ? $endDate
                    : Carbon::createFromTimeString($this->xWeekRange($date, 1) . ' 23:59:59');

                $timeIntervals[] = ['start' => $start, 'end' => $end, 'formatedDate' => $date->format('d M')];
            }
        } else {
            for ($i = 0; $i < $totalDays; $i++) {
                $date = clone $startDate;
                $date->addDays($i);

                $start = Carbon::createFromTimeString($date->format('Y-m-d') . ' 00:00:01');
                $end = Carbon::createFromTimeString($date->format('Y-m-d') . ' 23:59:59');

                $timeIntervals[] = ['start' => $start, 'end' => $end, 'formatedDate' => $date->format('d M')];
            }
        }

        return $timeIntervals;
    }

    /**
     *
     * @param string $date
     * @param int $day
     *
     * @return string
     */
    public function xWeekRange(string $date, int $day)
    {
        $ts = strtotime($date);

        if (!$day) {
            $start = (date('D', $ts) == 'Sun') ? $ts : strtotime('last sunday', $ts);

            return date('Y-m-d', $start);
        } else {
            $end = (date('D', $ts) == 'Sat') ? $ts : strtotime('next saturday', $ts);

            return date('Y-m-d', $end);
        }
    }

    /**
     * @param string $fieldName
     *
     * @return array
     */
    public function getConfigField(string $fieldName)
    {
        foreach (config('core') as $coreData) {
            if (isset($coreData['fields'])) {
                foreach ($coreData['fields'] as $field) {
                    $name = $coreData['key'] . '.' . $field['name'];

                    if ($name == $fieldName) {
                        return $field;
                    }
                }
            }
        }
    }


    /**
     * @param array $items
     *
     * @return array
     */
    public function convertToAssociativeArray($items)
    {
        foreach ($items as $key1 => $level1) {
            unset($items[$key1]);
            $items[$level1['key']] = $level1;

            if (count($level1['children'])) {
                foreach ($level1['children'] as $key2 => $level2) {
                    $temp2 = explode('.', $level2['key']);
                    $finalKey2 = end($temp2);
                    unset($items[$level1['key']]['children'][$key2]);
                    $items[$level1['key']]['children'][$finalKey2] = $level2;

                    if (count($level2['children'])) {
                        foreach ($level2['children'] as $key3 => $level3) {
                            $temp3 = explode('.', $level3['key']);
                            $finalKey3 = end($temp3);
                            unset($items[$level1['key']]['children'][$finalKey2]['children'][$key3]);
                            $items[$level1['key']]['children'][$finalKey2]['children'][$finalKey3] = $level3;
                        }
                    }

                }
            }
        }

        return $items;
    }

    /**
     * @param $array
     * @param string $key
     * @param string|int|float $value
     *
     * @return array
     */
    public function array_set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);
        $count = count($keys);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $finalKey = array_shift($keys);

        if (isset($array[$finalKey])) {
            $array[$finalKey] = $this->arrayMerge($array[$finalKey], $value);
        } else {
            $array[$finalKey] = $value;
        }

        return $array;
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    protected function arrayMerge(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->arrayMerge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * @param $array
     * @return array
     */
    public function convertEmptyStringsToNull(array $array = [])
    {
        foreach ($array as $key => $value) {
            if ($value == "" || $value == "null") {
                $array[$key] = null;
            }
        }

        return $array;
    }

    /**
     * Create singleton object through single facade
     *
     * @param string $className
     *
     * @return object
     */
    public function getSingletonInstance(string $className)
    {
        static $instance = [];

        if (array_key_exists($className, $instance)) {
            return $instance[$className];
        }

        return $instance[$className] = app($className);
    }
}
