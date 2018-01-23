<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('array_merge_relation')) {
    /**
     * Associate another dict to a dict based on a common key.
     *
     * @param array  $input
     * @param array  $relations
     * @param string $relation
     * @param string $column
     */
    function array_merge_relation(array &$input, array &$relations, $relation, $column)
    {
        foreach ($input as &$row) {
            if (isset($row[$column]) && isset($relations[$row[$column]])) {
                $row[$relation] = $relations[$row[$column]];
            } else {
                $row[$relation] = [];
            }
        }
    }
}

if (!function_exists('array_column_index')) {
    /**
     * Create indexed array from a list of dict.
     *
     * $input = [
     *   ['k1' => 1, 'k2' => 2], ['k1' => 3, 'k2' => 4], ['k1' => 1, 'k2' => 5]
     * ]
     *
     * array_column_index($input, 'k1') will returns:
     *
     * [
     *   1 => [['k1' => 1, 'k2' => 2], ['k1' => 1, 'k2' => 5]],
     *   3 => [['k1' => 3, 'k2' => 4]],
     * ]
     *
     * @param array  $input
     * @param string $column
     *
     * @return array
     */
    function array_column_index(array &$input, $column)
    {
        $result = [];

        foreach ($input as &$row) {
            if (isset($row[$column])) {
                $result[$row[$column]][] = $row;
            }
        }

        return $result;
    }
}

if (!function_exists('array_column_sum')) {
    /**
     * Sum all values from a single column in the input array.
     *
     * $input = [
     *   ['column' => 2], ['column' => 3]
     * ]
     *
     * array_column_sum($input, 'column') returns 5
     *
     * @param array  $input
     * @param string $column
     *
     * @return float
     */
    function array_column_sum(array &$input, $column)
    {
        $sum = 0.0;

        foreach ($input as &$row) {
            if (isset($row[$column])) {
                $sum += (float) $row[$column];
            }
        }

        return $sum;
    }
}

if (!function_exists('get_upload_max_size')) {
    /**
     * Get upload max size.
     *
     * @return string
     */
    function get_upload_max_size()
    {
        return min(ini_get('upload_max_filesize'), ini_get('post_max_size'));
    }
}

if (!function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param string $value
     * @param array  $options
     *
     * @return string
     */
    function bcrypt($value, array $options = [])
    {
        $cost = isset($options['rounds']) ? $options['rounds'] : 10;
        $hash = password_hash($value, PASSWORD_BCRYPT, ['cost' => $cost]);
        if ($hash === false) {
            trigger_error('Bcrypt hashing not supported.', E_USER_WARNING);

            return;
        }

        return $hash;
    }
}

if (!function_exists('t')) {
    /**
     * Translate a string.
     *
     * @return string
     */
    function t()
    {
        return call_user_func_array([\Jitamin\Foundation\Translator::getInstance(), 'translate'], func_get_args());
    }
}

if (!function_exists('l')) {
    /**
     * Translate a string with no HTML escaping language (raw data).
     *
     * @return string
     */
    function l()
    {
        return call_user_func_array([\Jitamin\Foundation\Translator::getInstance(), 'translateNoEscaping'], func_get_args());
    }
}

if (!function_exists('n')) {
    /**
     * Translate a number.
     *
     * @param mixed $value
     *
     * @return string
     */
    function n($value)
    {
        return \Jitamin\Foundation\Translator::getInstance()->number($value);
    }
}
if (! function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (strlen($value) > 1 && strpos($value, '"') === 0 && strrpos($value, '"') == strlen($value) -1) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
