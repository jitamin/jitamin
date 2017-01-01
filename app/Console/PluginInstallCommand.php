<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
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
 * Plugin install command class.
 */
class PluginInstallCommand extends BaseCommand
{
    /**
     * Configure the console command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('plugin:install')
            ->setDescription('Install a plugin from a remote Zip archive')
            ->addArgument('url', InputArgument::REQUIRED, 'Archive URL');
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface  $output
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!Installer::isConfigured()) {
            throw new LogicException('Jitamin is not configured to install plugins itself');
        }

        try {
            $installer = new Installer($this->container);
            $installer->install($input->getArgument('url'));
            $output->writeln('<info>Plugin installed successfully</info>');
        } catch (PluginInstallerException $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
        }
    }
}
