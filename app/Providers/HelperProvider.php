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

use Hiject\Core\Helper;
use Hiject\Core\Template;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class HelperProvider.
 */
class HelperProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['helper'] = new Helper($container);
        $container['helper']->register('app', '\Hiject\Helper\AppHelper');
        $container['helper']->register('calendar', '\Hiject\Helper\CalendarHelper');
        $container['helper']->register('asset', '\Hiject\Helper\AssetHelper');
        $container['helper']->register('board', '\Hiject\Helper\BoardHelper');
        $container['helper']->register('dt', '\Hiject\Helper\DateHelper');
        $container['helper']->register('file', '\Hiject\Helper\FileHelper');
        $container['helper']->register('form', '\Hiject\Helper\FormHelper');
        $container['helper']->register('hook', '\Hiject\Helper\HookHelper');
        $container['helper']->register('ical', '\Hiject\Helper\ICalHelper');
        $container['helper']->register('layout', '\Hiject\Helper\LayoutHelper');
        $container['helper']->register('model', '\Hiject\Helper\ModelHelper');
        $container['helper']->register('subtask', '\Hiject\Helper\SubtaskHelper');
        $container['helper']->register('task', '\Hiject\Helper\TaskHelper');
        $container['helper']->register('text', '\Hiject\Helper\TextHelper');
        $container['helper']->register('url', '\Hiject\Helper\UrlHelper');
        $container['helper']->register('user', '\Hiject\Helper\UserHelper');
        $container['helper']->register('avatar', '\Hiject\Helper\AvatarHelper');
        $container['helper']->register('projectRole', '\Hiject\Helper\ProjectRoleHelper');
        $container['helper']->register('projectHeader', '\Hiject\Helper\ProjectHeaderHelper');
        $container['helper']->register('projectActivity', '\Hiject\Helper\ProjectActivityHelper');
        $container['helper']->register('mail', '\Hiject\Helper\MailHelper');

        $container['template'] = new Template($container['helper']);

        return $container;
    }
}
