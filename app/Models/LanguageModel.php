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
use Hiject\Core\Translator;

/**
 * Class Language.
 */
class LanguageModel extends Base
{
    /**
     * Get all language codes.
     *
     * @static
     *
     * @return string[]
     */
    public static function getCodes()
    {
        return [
            'en_US',
            'zh_CN',
        ];
    }

    /**
     * Find language code.
     *
     * @static
     *
     * @param string $code
     *
     * @return string
     */
    public static function findCode($code)
    {
        $code = str_replace('-', '_', $code);

        return in_array($code, self::getCodes()) ? $code : '';
    }

    /**
     * Get available languages.
     *
     * @param bool $prepend Prepend a default value
     *
     * @return array
     */
    public function getLanguages($prepend = false)
    {
        // Sorted by value
        $languages = [
            'en_US' => 'English',
            'zh_CN' => '中文(简体)',
        ];

        if ($prepend) {
            return ['' => t('Use system language')] + $languages;
        }

        return $languages;
    }

    /**
     * Get javascript language code.
     *
     * @return string
     */
    public function getJsLanguageCode()
    {
        $languages = [
            'en_US' => 'en',
            'zh_CN' => 'zh-cn',
        ];

        $lang = $this->getCurrentLanguage();

        return isset($languages[$lang]) ? $languages[$lang] : 'en';
    }

    /**
     * Get current language.
     *
     * @return string
     */
    public function getCurrentLanguage()
    {
        if ($this->userSession->isLogged() && !empty($this->sessionStorage->user['language'])) {
            return $this->sessionStorage->user['language'];
        }

        return $this->configModel->get('application_language', 'en_US');
    }

    /**
     * Load translations for the current language.
     */
    public function loadCurrentLanguage()
    {
        Translator::load($this->getCurrentLanguage());
    }
}
