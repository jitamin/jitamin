<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation\Exceptions;

use Exception;

/**
 * Class AccessForbiddenException.
 */
class BaseException extends Exception
{
    protected $withoutLayout = false;

    /**
     * Get object instance.
     *
     * @static
     *
     * @param string $message
     *
     * @return static
     */
    public static function getInstance($message = '')
    {
        return new static($message);
    }

    /**
     * There is no layout.
     *
     * @return BaseException
     */
    public function withoutLayout()
    {
        $this->withoutLayout = true;

        return $this;
    }

    /**
     * Return true if no layout.
     *
     * @return bool
     */
    public function hasLayout()
    {
        return $this->withoutLayout;
    }
}
