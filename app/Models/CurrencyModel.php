<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Model;

use Hiject\Core\Base;

/**
 * Currency.
 */
class CurrencyModel extends Base
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'currencies';

    /**
     * Get available application currencies.
     *
     * @return array
     */
    public function getCurrencies()
    {
        return [
            'USD' => t('USD - US Dollar'),
            'EUR' => t('EUR - Euro'),
            'GBP' => t('GBP - British Pound'),
            'CHF' => t('CHF - Swiss Francs'),
            'CAD' => t('CAD - Canadian Dollar'),
            'AUD' => t('AUD - Australian Dollar'),
            'NZD' => t('NZD - New Zealand Dollar'),
            'INR' => t('INR - Indian Rupee'),
            'JPY' => t('JPY - Japanese Yen'),
            'RSD' => t('RSD - Serbian dinar'),
            'SEK' => t('SEK - Swedish Krona'),
            'NOK' => t('NOK - Norwegian Krone'),
            'BAM' => t('BAM - Konvertible Mark'),
            'RUB' => t('RUB - Russian Ruble'),
        ];
    }

    /**
     * Get all currency rates.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->db->table(self::TABLE)->findAll();
    }

    /**
     * Calculate the price for the reference currency.
     *
     * @param string $currency
     * @param float  $price
     *
     * @return float
     */
    public function getPrice($currency, $price)
    {
        static $rates = null;
        $reference = $this->configModel->get('application_currency', 'USD');

        if ($reference !== $currency) {
            $rates = $rates === null ? $this->db->hashtable(self::TABLE)->getAll('currency', 'rate') : $rates;
            $rate = isset($rates[$currency]) ? $rates[$currency] : 1;

            return $rate * $price;
        }

        return $price;
    }

    /**
     * Add a new currency rate.
     *
     * @param string $currency
     * @param float  $rate
     *
     * @return bool|int
     */
    public function create($currency, $rate)
    {
        if ($this->db->table(self::TABLE)->eq('currency', $currency)->exists()) {
            return $this->update($currency, $rate);
        }

        return $this->db->table(self::TABLE)->insert(['currency' => $currency, 'rate' => $rate]);
    }

    /**
     * Update a currency rate.
     *
     * @param string $currency
     * @param float  $rate
     *
     * @return bool
     */
    public function update($currency, $rate)
    {
        return $this->db->table(self::TABLE)->eq('currency', $currency)->update(['rate' => $rate]);
    }
}
