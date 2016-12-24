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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Reset two factor command class.
 */
class ResetTwoFactorCommand extends BaseCommand
{
    /**
     * Configure the console command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('user:reset-2fa')
            ->setDescription('Remove two-factor authentication for a user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username');
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
        $username = $input->getArgument('username');
        $userId = $this->userModel->getIdByUsername($username);

        if (empty($userId)) {
            $output->writeln('<error>User not found</error>');

            return 1;
        }

        if (!$this->userModel->update(['id' => $userId, 'twofactor_activated' => 0, 'twofactor_secret' => ''])) {
            $output->writeln('<error>Unable to update user profile</error>');

            return 1;
        }

        $output->writeln('<info>Two-factor authentication disabled</info>');
    }
}
