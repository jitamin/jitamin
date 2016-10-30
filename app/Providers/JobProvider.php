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

use Hiject\Bus\Job\CommentEventJob;
use Hiject\Bus\Job\NotificationJob;
use Hiject\Bus\Job\ProjectFileEventJob;
use Hiject\Bus\Job\ProjectMetricJob;
use Hiject\Bus\Job\SubtaskEventJob;
use Hiject\Bus\Job\TaskEventJob;
use Hiject\Bus\Job\TaskFileEventJob;
use Hiject\Bus\Job\TaskLinkEventJob;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class JobProvider
 */
class JobProvider implements ServiceProviderInterface
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
        $container['commentEventJob'] = $container->factory(function ($c) {
            return new CommentEventJob($c);
        });

        $container['subtaskEventJob'] = $container->factory(function ($c) {
            return new SubtaskEventJob($c);
        });

        $container['taskEventJob'] = $container->factory(function ($c) {
            return new TaskEventJob($c);
        });

        $container['taskFileEventJob'] = $container->factory(function ($c) {
            return new TaskFileEventJob($c);
        });

        $container['taskLinkEventJob'] = $container->factory(function ($c) {
            return new TaskLinkEventJob($c);
        });

        $container['projectFileEventJob'] = $container->factory(function ($c) {
            return new ProjectFileEventJob($c);
        });

        $container['notificationJob'] = $container->factory(function ($c) {
            return new NotificationJob($c);
        });

        $container['projectMetricJob'] = $container->factory(function ($c) {
            return new ProjectMetricJob($c);
        });

        return $container;
    }
}
