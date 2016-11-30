<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\Event;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

/**
 * Authentication success event.
 */
class AuthSuccessEvent extends BaseEvent
{
    /**
     * Authentication provider name.
     *
     * @var string
     */
    private $authType;

    /**
     * Constructor.
     *
     * @param string $authType
     */
    public function __construct($authType)
    {
        $this->authType = $authType;
    }

    /**
     * Get authentication type.
     *
     * @return string
     */
    public function getAuthType()
    {
        return $this->authType;
    }
}
