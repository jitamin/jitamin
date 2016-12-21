<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Providers;

use Jitamin\Core\Helper;
use Jitamin\Core\Template;
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
        $container['helper']->register('app', '\Jitamin\Helper\AppHelper');
        $container['helper']->register('calendar', '\Jitamin\Helper\CalendarHelper');
        $container['helper']->register('asset', '\Jitamin\Helper\AssetHelper');
        $container['helper']->register('board', '\Jitamin\Helper\BoardHelper');
        $container['helper']->register('dt', '\Jitamin\Helper\DateHelper');
        $container['helper']->register('file', '\Jitamin\Helper\FileHelper');
        $container['helper']->register('form', '\Jitamin\Helper\FormHelper');
        $container['helper']->register('hook', '\Jitamin\Helper\HookHelper');
        $container['helper']->register('ical', '\Jitamin\Helper\ICalHelper');
        $container['helper']->register('layout', '\Jitamin\Helper\LayoutHelper');
        $container['helper']->register('model', '\Jitamin\Helper\ModelHelper');
        $container['helper']->register('subtask', '\Jitamin\Helper\SubtaskHelper');
        $container['helper']->register('task', '\Jitamin\Helper\TaskHelper');
        $container['helper']->register('text', '\Jitamin\Helper\TextHelper');
        $container['helper']->register('url', '\Jitamin\Helper\UrlHelper');
        $container['helper']->register('user', '\Jitamin\Helper\UserHelper');
        $container['helper']->register('avatar', '\Jitamin\Helper\AvatarHelper');
        $container['helper']->register('projectRole', '\Jitamin\Helper\ProjectRoleHelper');
        $container['helper']->register('projectHeader', '\Jitamin\Helper\ProjectHeaderHelper');
        $container['helper']->register('projectActivity', '\Jitamin\Helper\ProjectActivityHelper');
        $container['helper']->register('mail', '\Jitamin\Helper\MailHelper');

        $container['template'] = new Template($container['helper']);

        return $container;
    }
}
