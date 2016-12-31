<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Filter;

use Jitamin\Core\DateParser;

/**
 * Base date filter class.
 */
abstract class BaseDateFilter extends BaseFilter
{
    /**
     * DateParser object.
     *
     * @var DateParser
     */
    protected $dateParser;

    /**
     * Set DateParser object.
     *
     * @param DateParser $dateParser
     *
     * @return $this
     */
    public function setDateParser(DateParser $dateParser)
    {
        $this->dateParser = $dateParser;

        return $this;
    }

    /**
     * Parse operator in the input string.
     *
     * @return string
     */
    protected function parseOperator()
    {
        $operators = [
            '<=' => 'lte',
            '>=' => 'gte',
            '<'  => 'lt',
            '>'  => 'gt',
        ];

        foreach ($operators as $operator => $method) {
            if (strpos($this->value, $operator) === 0) {
                $this->value = substr($this->value, strlen($operator));

                return $method;
            }
        }

        return '';
    }

    /**
     * Apply a date filter.
     *
     * @param string $field
     */
    protected function applyDateFilter($field)
    {
        $method = $this->parseOperator();
        $timestamp = $this->dateParser->getTimestampFromIsoFormat($this->value);

        if ($method !== '') {
            $this->query->$method($field, $this->getTimestampFromOperator($method, $timestamp));
        } else {
            $this->query->gte($field, $timestamp);
            $this->query->lte($field, $timestamp + 86399);
        }
    }

    /**
     * Get timestamp from the operator.
     *
     * @param string $method
     * @param int    $timestamp
     *
     * @return int
     */
    protected function getTimestampFromOperator($method, $timestamp)
    {
        switch ($method) {
            case 'lte':
                return $timestamp + 86399;
            case 'lt':
                return $timestamp;
            case 'gte':
                return $timestamp;
            case 'gt':
                return $timestamp + 86400;
        }

        return $timestamp;
    }
}
