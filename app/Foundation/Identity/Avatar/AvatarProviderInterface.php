<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation\Identity\Avatar;

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
