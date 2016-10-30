<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\User\Avatar;

/**
 * Avatar Provider Interface
 */
interface AvatarProviderInterface
{
    /**
     * Render avatar html
     *
     * @access public
     * @param  array $user
     * @param  int   $size
     */
    public function render(array $user, $size);

    /**
     * Determine if the provider is active
     *
     * @access public
     * @param  array $user
     * @return boolean
     */
    public function isActive(array $user);
}
