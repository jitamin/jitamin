<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Session;

use Jitamin\Core\Base;

/**
 * Session Flash Message.
 */
class FlashMessage extends Base
{
    /**
     * Add success message.
     *
     * @param string $message
     */
    public function success($message)
    {
        $this->setMessage('success', $message);
    }

    /**
     * Add failure message.
     *
     * @param string $message
     */
    public function failure($message)
    {
        $this->setMessage('failure', $message);
    }

    /**
     * Add new flash message.
     *
     * @param string $key
     * @param string $message
     */
    public function setMessage($key, $message)
    {
        if (!isset($this->sessionStorage->flash)) {
            $this->sessionStorage->flash = [];
        }

        $this->sessionStorage->flash[$key] = $message;
    }

    /**
     * Get flash message.
     *
     * @param string $key
     *
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
