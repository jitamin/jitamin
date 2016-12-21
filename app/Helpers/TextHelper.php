<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Helper;

use Jitamin\Core\Base;
use Jitamin\Core\Markdown;

/**
 * Text Helpers.
 */
class TextHelper extends Base
{
    /**
     * HTML escaping.
     *
     * @param string $value Value to escape
     *
     * @return string
     */
    public function e($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * Markdown transformation.
     *
     * @param string $text
     * @param bool   $isPublicLink
     *
     * @return string
     */
    public function markdown($text, $isPublicLink = false)
    {
        $parser = new Markdown($this->container, $isPublicLink);
        $parser->setMarkupEscaped(MARKDOWN_ESCAPE_HTML);

        return $parser->text($text);
    }

    /**
     * Escape Markdown text that need to be stored in HTML attribute.
     *
     * @param string $text
     *
     * @return mixed
     */
    public function markdownAttribute($text)
    {
        return htmlentities($this->markdown($text), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Format a file size.
     *
     * @param int $size      Size in bytes
     * @param int $precision Precision
     *
     * @return string
     */
    public function bytes($size, $precision = 2)
    {
        $base = log($size) / log(1024);
        $suffixes = ['', 'k', 'M', 'G', 'T'];

        return round(pow(1024, $base - floor($base)), $precision).$suffixes[(int) floor($base)];
    }

    /**
     * Get the number of bytes from PHP size.
     *
     * @param int $val PHP size (example: 2M)
     *
     * @return int
     */
    public function phpToBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);

        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    /**
     * Return true if needle is contained in the haystack.
     *
     * @param string $haystack Haystack
     * @param string $needle   Needle
     *
     * @return bool
     */
    public function contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }

    /**
     * Return a value from a dictionary.
     *
     * @param mixed  $id            Key
     * @param array  $listing       Dictionary
     * @param string $default_value Value displayed when the key doesn't exists
     *
     * @return string
     */
    public function in($id, array $listing, $default_value = '?')
    {
        if (isset($listing[$id])) {
            return $this->helper->text->e($listing[$id]);
        }

        return $default_value;
    }
}
