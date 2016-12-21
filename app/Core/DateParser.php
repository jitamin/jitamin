<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core;

use DateTime;

/**
 * Date Parser.
 */
class DateParser extends Base
{
    const DATE_FORMAT = 'm/d/Y';
    const DATE_TIME_FORMAT = 'm/d/Y H:i';
    const TIME_FORMAT = 'H:i';

    /**
     * Get date format from settings.
     *
     * @return string
     */
    public function getUserDateFormat()
    {
        return $this->settingModel->get('application_date_format', self::DATE_FORMAT);
    }

    /**
     * Get date time format from settings.
     *
     * @return string
     */
    public function getUserDateTimeFormat()
    {
        return $this->settingModel->get('application_datetime_format', self::DATE_TIME_FORMAT);
    }

    /**
     * Get time format from settings.
     *
     * @return string
     */
    public function getUserTimeFormat()
    {
        return $this->settingModel->get('application_time_format', self::TIME_FORMAT);
    }

    /**
     * List of time formats.
     *
     * @return string[]
     */
    public function getTimeFormats()
    {
        return [
            'H:i',
            'g:i a',
        ];
    }

    /**
     * List of date formats.
     *
     * @param bool $iso
     *
     * @return string[]
     */
    public function getDateFormats($iso = false)
    {
        $formats = [
            $this->getUserDateFormat(),
        ];

        $isoFormats = [
            'Y-m-d',
            'Y_m_d',
        ];

        $userFormats = [
            'm/d/Y',
            'd/m/Y',
            'Y/m/d',
            'd.m.Y',
        ];

        if ($iso) {
            $formats = array_merge($formats, $isoFormats, $userFormats);
        } else {
            $formats = array_merge($formats, $userFormats);
        }

        return array_unique($formats);
    }

    /**
     * List of datetime formats.
     *
     * @param bool $iso
     *
     * @return string[]
     */
    public function getDateTimeFormats($iso = false)
    {
        $formats = [
            $this->getUserDateTimeFormat(),
        ];

        foreach ($this->getDateFormats($iso) as $date) {
            foreach ($this->getTimeFormats() as $time) {
                $formats[] = $date.' '.$time;
            }
        }

        return array_unique($formats);
    }

    /**
     * List of all date formats.
     *
     * @param bool $iso
     *
     * @return string[]
     */
    public function getAllDateFormats($iso = false)
    {
        return array_merge($this->getDateFormats($iso), $this->getDateTimeFormats($iso));
    }

    /**
     * Get available formats (visible in settings).
     *
     * @param array $formats
     *
     * @return array
     */
    public function getAvailableFormats(array $formats)
    {
        $values = [];

        foreach ($formats as $format) {
            $values[$format] = date($format).' ('.$format.')';
        }

        return $values;
    }

    /**
     * Get formats for date parsing.
     *
     * @return array
     */
    public function getParserFormats()
    {
        return [
            $this->getUserDateFormat(),
            'Y-m-d',
            'Y_m_d',
            $this->getUserDateTimeFormat(),
            'Y-m-d H:i',
            'Y_m_d H:i',
        ];
    }

    /**
     * Parse a date and return a unix timestamp, try different date formats.
     *
     * @param string $value Date to parse
     *
     * @return int
     */
    public function getTimestamp($value)
    {
        if (ctype_digit($value)) {
            return (int) $value;
        }

        foreach ($this->getParserFormats() as $format) {
            $timestamp = $this->getValidDate($value, $format);

            if ($timestamp !== 0) {
                return $timestamp;
            }
        }

        return 0;
    }

    /**
     * Return a timestamp if the given date format is correct otherwise return 0.
     *
     * @param string $value  Date to parse
     * @param string $format Date format
     *
     * @return int
     */
    private function getValidDate($value, $format)
    {
        $date = DateTime::createFromFormat($format, $value);

        if ($date !== false) {
            $errors = DateTime::getLastErrors();
            if ($errors['error_count'] === 0 && $errors['warning_count'] === 0) {
                $timestamp = $date->getTimestamp();

                return $timestamp > 0 ? $timestamp : 0;
            }
        }

        return 0;
    }

    /**
     * Return true if the date is within the date range.
     *
     * @param DateTime $date
     * @param DateTime $start
     * @param DateTime $end
     *
     * @return bool
     */
    public function withinDateRange(DateTime $date, DateTime $start, DateTime $end)
    {
        return $date >= $start && $date <= $end;
    }

    /**
     * Get the total number of hours between 2 datetime objects
     * Minutes are rounded to the nearest quarter.
     *
     * @param DateTime $d1
     * @param DateTime $d2
     *
     * @return float
     */
    public function getHours(DateTime $d1, DateTime $d2)
    {
        $seconds = abs($d1->getTimestamp() - $d2->getTimestamp());

        return round($seconds / 3600, 2);
    }

    /**
     * Get ISO-8601 date from user input.
     *
     * @param string $value Date to parse
     *
     * @return string
     */
    public function getIsoDate($value)
    {
        return date('Y-m-d', $this->getTimestamp($value));
    }

    /**
     * Get a timestamp from an ISO date format.
     *
     * @param string $value
     *
     * @return int
     */
    public function getTimestampFromIsoFormat($value)
    {
        return $this->removeTimeFromTimestamp(ctype_digit($value) ? $value : strtotime($value));
    }

    /**
     * Remove the time from a timestamp.
     *
     * @param int $timestamp
     *
     * @return int
     */
    public function removeTimeFromTimestamp($timestamp)
    {
        return mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp), date('Y', $timestamp));
    }

    /**
     * Format date (form display).
     *
     * @param array    $values Database values
     * @param string[] $fields Date fields
     * @param string   $format Date format
     *
     * @return array
     */
    public function format(array $values, array $fields, $format)
    {
        foreach ($fields as $field) {
            if (!empty($values[$field])) {
                if (!ctype_digit($values[$field])) {
                    $values[$field] = strtotime($values[$field]);
                }

                $values[$field] = date($format, $values[$field]);
            } else {
                $values[$field] = '';
            }
        }

        return $values;
    }

    /**
     * Convert date to timestamp.
     *
     * @param array    $values    Database values
     * @param string[] $fields    Date fields
     * @param bool     $keep_time Keep time or not
     *
     * @return array
     */
    public function convert(array $values, array $fields, $keep_time = false)
    {
        foreach ($fields as $field) {
            if (!empty($values[$field])) {
                $timestamp = $this->getTimestamp($values[$field]);
                $values[$field] = $keep_time ? $timestamp : $this->removeTimeFromTimestamp($timestamp);
            }
        }

        return $values;
    }
}
