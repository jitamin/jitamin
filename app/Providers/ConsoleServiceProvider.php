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

use Jitamin\Console\ConfigCacheCommand;
use Jitamin\Console\CronjobCommand;
use Jitamin\Console\PluginInstallCommand;
use Jitamin\Console\PluginUninstallCommand;
use Jitamin\Console\PluginUpgradeCommand;
use Jitamin\Console\ProjectDailyColumnStatsExportCommand;
use Jitamin\Console\ProjectDailyStatsCalculationCommand;
use Jitamin\Console\ResetPasswordCommand;
use Jitamin\Console\ResetTwoFactorCommand;
use Jitamin\Console\RouteCacheCommand;
use Jitamin\Console\SubtaskExportCommand;
use Jitamin\Console\TaskExportCommand;
use Jitamin\Console\TaskOverdueNotificationCommand;
use Jitamin\Console\TaskTriggerCommand;
use Jitamin\Console\TransitionExportCommand;
use Jitamin\Console\WorkerCommand;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;

/**
 * Class of Console Service Provider.
 */
class ConsoleServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * @param Container $container
     *
     * @return Container
     */
    public function register(Container $container)
    {
        $application = new Application('Jitamin', APP_VERSION);
        $application->add(new ConfigCacheCommand($container));
        $application->add(new RouteCacheCommand($container));
        $application->add(new TaskOverdueNotificationCommand($container));
        $application->add(new SubtaskExportCommand($container));
        $application->add(new TaskExportCommand($container));
        $application->add(new ProjectDailyStatsCalculationCommand($container));
        $application->add(new ProjectDailyColumnStatsExportCommand($container));
        $application->add(new TransitionExportCommand($container));
        $application->add(new TaskTriggerCommand($container));
        $application->add(new CronjobCommand($container));
        $application->add(new WorkerCommand($container));
        $application->add(new ResetPasswordCommand($container));
        $application->add(new ResetTwoFactorCommand($container));
        $application->add(new PluginUpgradeCommand($container));
        $application->add(new PluginInstallCommand($container));
        $application->add(new PluginUninstallCommand($container));

        $container['cli'] = $application;

        return $container;
    }
}
