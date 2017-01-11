<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Providers;

use Jitamin\Bus\Job\CommentEventJob;
use Jitamin\Bus\Job\NotificationJob;
use Jitamin\Bus\Job\ProjectFileEventJob;
use Jitamin\Bus\Job\ProjectMetricJob;
use Jitamin\Bus\Job\SubtaskEventJob;
use Jitamin\Bus\Job\TaskEventJob;
use Jitamin\Bus\Job\TaskFileEventJob;
use Jitamin\Bus\Job\TaskLinkEventJob;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class of Job Service Provider.
 */
class JobServiceProvider implements ServiceProviderInterface
{
    /**
     * Register providers.
     *
     * @param \Pimple\Container $container
     *
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
