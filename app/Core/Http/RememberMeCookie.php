<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Http;

use Jitamin\Core\Base;

/**
 * Remember Me Cookie.
 */
class RememberMeCookie extends Base
{
    /**
     * Cookie name.
     *
     * @var string
     */
    const COOKIE_NAME = 'HJ_RM';

    /**
     * Encode the cookie.
     *
     * @param string $token    Session token
     * @param string $sequence Sequence token
     *
     * @return string
     */
    public function encode($token, $sequence)
    {
        return implode('|', [$token, $sequence]);
    }

    /**
     * Decode the value of a cookie.
     *
     * @param string $value Raw cookie data
     *
     * @return array
     */
    public function decode($value)
    {
        list($token, $sequence) = explode('|', $value);

        return [
            'token'    => $token,
            'sequence' => $sequence,
        ];
    }

    /**
     * Return true if the current user has a RememberMe cookie.
     *
     * @return bool
     */
    public function hasCookie()
    {
        return $this->request->getCookie(self::COOKIE_NAME) !== '';
    }

    /**
     * Write and encode the cookie.
     *
     * @param string $token      Session token
     * @param string $sequence   Sequence token
     * @param string $expiration Cookie expiration
     *
     * @return bool
     */
    public function write($token, $sequence, $expiration)
    {
        return setcookie(
            self::COOKIE_NAME,
            $this->encode($token, $sequence),
            $expiration,
            $this->helper->url->dir(),
            null,
            $this->request->isHTTPS(),
            true
        );
    }

    /**
     * Read and decode the cookie.
     *
     * @return mixed
     */
    public function read()
    {
        $cookie = $this->request->getCookie(self::COOKIE_NAME);

        if (empty($cookie)) {
            return false;
        }

        return $this->decode($cookie);
    }

    /**
     * Remove the cookie.
     *
     * @return bool
     */
    public function remove()
    {
        return setcookie(
            self::COOKIE_NAME,
            '',
            time() - 3600,
            $this->helper->url->dir(),
            null,
            $this->request->isHTTPS(),
            true
        );
    }
}
