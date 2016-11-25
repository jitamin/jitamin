<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Session;

use Hiject\Core\Base;

/**
 * Session Flash Message
 */
class FlashMessage extends Base
{
    /**
     * Add success message
     *
     * @access public
     * @param  string  $message
     */
    public function success($message)
    {
        $this->setMessage('success', $message);
    }

    /**
     * Add failure message
     *
     * @access public
     * @param  string  $message
     */
    public function failure($message)
    {
        $this->setMessage('failure', $message);
    }

    /**
     * Add new flash message
     *
     * @access public
     * @param  string  $key
     * @param  string  $message
     */
    public function setMessage($key, $message)
    {
        if (! isset($this->sessionStorage->flash)) {
            $this->sessionStorage->flash = [];
        }

        $this->sessionStorage->flash[$key] = $message;
    }

    /**
     * Get flash message
     *
     * @access public
     * @param  string  $key
     * @return string
     */
    public function getMessage($key)
    {
        $message = '';

        if (isset($this->sessionStorage->flash[$key])) {
            $message = $this->sessionStorage->flash[$key];
            unset($this->sessionStorage->flash[$key]);
        }

        return $message;
    }
}
