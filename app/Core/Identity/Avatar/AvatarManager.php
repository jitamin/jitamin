<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Identity\Avatar;

/**
 * Avatar Manager.
 */
class AvatarManager
{
    /**
     * Providers.
     *
     * @var AvatarProviderInterface[]
     */
    private $providers = [];

    /**
     * Register a new Avatar provider.
     *
     * @param AvatarProviderInterface $provider
     *
     * @return $this
     */
    public function register(AvatarProviderInterface $provider)
    {
        $this->providers[] = $provider;

        return $this;
    }

    /**
     * Render avatar HTML element.
     *
     * @param string $user_id
     * @param string $username
     * @param string $name
     * @param string $email
     * @param string $avatar_path
     * @param int    $size
     *
     * @return string
     */
    public function render($user_id, $username, $name, $email, $avatar_path, $size)
    {
        $user = [
            'id'          => $user_id,
            'username'    => $username,
            'name'        => $name,
            'email'       => $email,
            'avatar_path' => $avatar_path,
        ];

        krsort($this->providers);

        foreach ($this->providers as $provider) {
            if ($provider->isActive($user)) {
                return $provider->render($user, $size);
            }
        }

        return '';
    }

    /**
     * Render default provider for unknown users (first provider registered).
     *
     * @param int $size
     *
     * @return string
     */
    public function renderDefault($size)
    {
        if (count($this->providers) > 0) {
            ksort($this->providers);
            $provider = current($this->providers);

            $user = [
                'id'          => 0,
                'username'    => '',
                'name'        => '?',
                'email'       => '',
                'avatar_path' => '',
            ];

            return $provider->render($user, $size);
        }

        return '';
    }
}
