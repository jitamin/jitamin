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
 * Avatar Provider Interface.
 */
interface AvatarProviderInterface
{
    /**
     * Render avatar html.
     *
     * @param array $user
     * @param int   $size
     */
    public function render(array $user, $size);

    /**
     * Determine if the provider is active.
     *
     * @param array $user
     *
     * @return bool
     */
    public function isActive(array $user);
}
