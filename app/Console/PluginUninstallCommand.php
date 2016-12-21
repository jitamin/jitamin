<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Console;

use Jitamin\Core\Plugin\Installer;
use Jitamin\Core\Plugin\PluginInstallerException;
use LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Plugin uninstall command class.
 */
class PluginUninstallCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('plugin:uninstall')
            ->setDescription('Remove a plugin')
            ->addArgument('pluginId', InputArgument::REQUIRED, 'Plugin directory name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!Installer::isConfigured()) {
            throw new LogicException('Jitamin is not configured to install plugins itself');
        }

        try {
            $installer = new Installer($this->container);
            $installer->uninstall($input->getArgument('pluginId'));
            $output->writeln('<info>Plugin removed successfully</info>');
        } catch (PluginInstallerException $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }
    }
}
