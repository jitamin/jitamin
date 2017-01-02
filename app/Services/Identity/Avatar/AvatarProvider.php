<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Services\Identity\Avatar;

use Jitamin\Core\Base;
use Jitamin\Core\Identity\Avatar\AvatarProviderInterface;

/**
 * Avatar Local Image File Provider.
 */
class AvatarProvider extends Base implements AvatarProviderInterface
{
    /**
     * Render avatar html.
     *
     * @param array $user
     * @param int   $size
     *
     * @return string
     */
    public function render(array $user, $size)
    {
        $url = $this->helper->url->href('Profile/AvatarController', 'image', ['user_id' => $user['id'], 'size' => $size]);
        $title = $this->helper->text->e($user['name'] ?: $user['username']);

        return '<img src="'.$url.'" alt="'.$title.'" title="'.$title.'">';
    }

    /**
     * Determine if the provider is active.
     *
     * @param array $user
     *
     * @return bool
     */
    public function isActive(array $user)
    {
        return !empty($user['avatar_path']);
    }
}
