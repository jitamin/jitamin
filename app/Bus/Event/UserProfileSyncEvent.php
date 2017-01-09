<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Event;

use Jitamin\Foundation\Identity\UserProviderInterface;
use Jitamin\Services\Identity\LdapUserProvider;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserProfileSyncEvent.
 */
class UserProfileSyncEvent extends Event
{
    /**
     * User profile.
     *
     * @var array
     */
    private $profile;

    /**
     * User provider.
     *
     * @var UserProviderInterface
     */
    private $user;

    /**
     * UserProfileSyncEvent constructor.
     *
     * @param array                 $profile
     * @param UserProviderInterface $user
     */
    public function __construct(array $profile, UserProviderInterface $user)
    {
        $this->profile = $profile;
        $this->user = $user;
    }

    /**
     * Get user profile.
     *
     * @return array
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Get user provider object.
     *
     * @return UserProviderInterface|LdapUserProvider
     */
    public function getUser()
    {
        return $this->user;
    }
}
