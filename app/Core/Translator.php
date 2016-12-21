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

/**
 * Translator class.
 */
class Translator
{
    /**
     * Locale path.
     *
     * @var string
     */
    const PATH = '../resources/lang';

    /**
     * Locale.
     *
     * @static
     *
     * @var array
     */
    private static $locales = [];

    /**
     * Instance.
     *
     * @static
     *
     * @var Translator
     */
    private static $instance = null;

    /**
     * Get instance.
     *
     * @static
     *
     * @return Translator
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get a translation.
     *
     * $translator->translate('I have %d kids', 5);
     *
     * @param string $identifier Default string
     *
     * @return string
     */
    public function translate($identifier)
    {
        $args = func_get_args();

        array_shift($args);
        array_unshift($args, $this->get($identifier, $identifier));

        foreach ($args as &$arg) {
            $arg = htmlspecialchars($arg, ENT_QUOTES, 'UTF-8', false);
        }

        return call_user_func_array(
            'sprintf',
            $args
        );
    }

    /**
     * Get a translation with no HTML escaping.
     *
     * $translator->translateNoEscaping('I have %d kids', 5);
     *
     * @param string $identifier Default string
     *
     * @return string
     */
    public function translateNoEscaping($identifier)
    {
        $args = func_get_args();

        array_shift($args);
        array_unshift($args, $this->get($identifier, $identifier));

        return call_user_func_array(
            'sprintf',
            $args
        );
    }

    /**
     * Get a formatted number.
     *
     * $translator->number(1234.56);
     *
     * @param float $number Number to format
     *
     * @return string
     */
    public function number($number)
    {
        return number_format(
            $number,
            $this->get('number.decimals', 2),
            $this->get('number.decimals_separator', '.'),
            $this->get('number.thousands_separator', ',')
        );
    }

    /**
     * Get a formatted currency number.
     *
     * $translator->currency(1234.56);
     *
     * @param float $amount Number to format
     *
     * @return string
     */
    public function currency($amount)
    {
        $position = $this->get('currency.position', 'before');
        $symbol = $this->get('currency.symbol', '$');
        $str = '';

        if ($position === 'before') {
            $str .= $symbol;
        }

        $str .= $this->number($amount);

        if ($position === 'after') {
            $str .= ' '.$symbol;
        }

        return $str;
    }

    /**
     * Get an identifier from the translations or return the default.
     *
     * @param string $identifier Locale identifier
     * @param string $default    Default value
     *
     * @return string
     */
    public function get($identifier, $default = '')
    {
        if (isset(self::$locales[$identifier])) {
            return self::$locales[$identifier];
        } else {
            return $default;
        }
    }

    /**
     * Load translations.
     *
     * @static
     *
     * @param string $language Locale code: fr_FR
     * @param string $path     Locale folder
     */
    public static function load($language, $path = self::PATH)
    {
        foreach (glob($path.DIRECTORY_SEPARATOR.$language.DIRECTORY_SEPARATOR.'*.php') as $file) {
            self::$locales = array_merge(self::$locales, require($file));
        }
    }

    /**
     * Clear locales stored in memory.
     *
     * @static
     */
    public static function unload()
    {
        self::$locales = [];
    }
}
