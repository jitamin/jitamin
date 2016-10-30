<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Hiject\Core\User\Avatar\AvatarManager;
use Hiject\User\Avatar\GravatarProvider;
use Hiject\User\Avatar\AvatarFileProvider;
use Hiject\User\Avatar\LetterAvatarProvider;

/**
 * Avatar Provider
 */
class AvatarProvider implements ServiceProviderInterface
{
    /**
     * Register providers
     *
     * @access public
     * @param  \Pimple\Container $container
     * @return \Pimple\Container
     */
    public function register(Container $container)
    {
        $container['avatarManager'] = new AvatarManager;
        $container['avatarManager']->register(new LetterAvatarProvider($container));
        $container['avatarManager']->register(new GravatarProvider($container));
        $container['avatarManager']->register(new AvatarFileProvider($container));
        return $container;
    }
}
