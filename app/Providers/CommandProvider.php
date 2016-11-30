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

use Hiject\Console\CronjobCommand;
use Hiject\Console\LocaleComparatorCommand;
use Hiject\Console\LocaleSyncCommand;
use Hiject\Console\PluginInstallCommand;
use Hiject\Console\PluginUninstallCommand;
use Hiject\Console\PluginUpgradeCommand;
use Hiject\Console\ProjectDailyColumnStatsExportCommand;
use Hiject\Console\ProjectDailyStatsCalculationCommand;
use Hiject\Console\ResetPasswordCommand;
use Hiject\Console\ResetTwoFactorCommand;
use Hiject\Console\SubtaskExportCommand;
use Hiject\Console\TaskExportCommand;
use Hiject\Console\TaskOverdueNotificationCommand;
use Hiject\Console\TaskTriggerCommand;
use Hiject\Console\TransitionExportCommand;
use Hiject\Console\WorkerCommand;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;

/**
 * Class CommandProvider.
 */
class CommandProvider implements ServiceProviderInterface
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
        $application = new Application('Hiject', APP_VERSION);
        $application->add(new TaskOverdueNotificationCommand($container));
        $application->add(new SubtaskExportCommand($container));
        $application->add(new TaskExportCommand($container));
        $application->add(new ProjectDailyStatsCalculationCommand($container));
        $application->add(new ProjectDailyColumnStatsExportCommand($container));
        $application->add(new TransitionExportCommand($container));
        $application->add(new LocaleSyncCommand($container));
        $application->add(new LocaleComparatorCommand($container));
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
