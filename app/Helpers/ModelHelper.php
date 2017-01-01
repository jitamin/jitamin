<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Helper;

use Jitamin\Core\Base;

/**
 * Model Helper.
 */
class ModelHelper extends Base
{
    /**
     * Remove keys from an array.
     *
     * @param array    $values Input array
     * @param string[] $keys   List of keys to remove
     */
    public function removeFields(array &$values, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $values)) {
                unset($values[$key]);
            }
        }
    }

    /**
     * Remove keys from an array if empty.
     *
     * @param array    $values Input array
     * @param string[] $keys   List of keys to remove
     */
    public function removeEmptyFields(array &$values, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $values) && empty($values[$key])) {
                unset($values[$key]);
            }
        }
    }

    /**
     * Force fields to be at 0 if empty.
     *
     * @param array    $values Input array
     * @param string[] $keys   List of keys
     */
    public function resetFields(array &$values, array $keys)
    {
        foreach ($keys as $key) {
            if (isset($values[$key]) && empty($values[$key])) {
                $values[$key] = 0;
            }
        }
    }

    /**
     * Force some fields to be integer.
     *
     * @param array    $values Input array
     * @param string[] $keys   List of keys
     */
    public function convertIntegerFields(array &$values, array $keys)
    {
        foreach ($keys as $key) {
            if (isset($values[$key])) {
                $values[$key] = (int) $values[$key];
            }
        }
    }

    /**
     * Force some fields to be null if empty.
     *
     * @param array    $values Input array
     * @param string[] $keys   List of keys
     */
    public function convertNullFields(array &$values, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $values) && empty($values[$key])) {
                $values[$key] = null;
            }
        }
    }
}
