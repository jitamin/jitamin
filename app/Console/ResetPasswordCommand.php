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
use Symfony\Component\Console\Question\Question;

/**
 * Reset password command class.
 */
class ResetPasswordCommand extends BaseCommand
{
    /**
     * Configure the console command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('user:reset-password')
            ->setDescription('Change user password')
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
        $helper = $this->getHelper('question');
        $username = $input->getArgument('username');

        $passwordQuestion = new Question('What is the new password for '.$username.'? (characters are not printed)'.PHP_EOL);
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        $password = $helper->ask($input, $output, $passwordQuestion);

        $confirmationQuestion = new Question('Confirmation:'.PHP_EOL);
        $confirmationQuestion->setHidden(true);
        $confirmationQuestion->setHiddenFallback(false);

        $confirmation = $helper->ask($input, $output, $confirmationQuestion);

        if ($this->validatePassword($output, $password, $confirmation)) {
            $this->resetPassword($output, $username, $password);
        }
    }

    /**
     * Validate the given password.
     *
     * @param OutputInterface $output
     * @param string          $password
     * @param string          $confirmation
     *
     * @return bool
     */
    private function validatePassword(OutputInterface $output, $password, $confirmation)
    {
        list($valid, $errors) = $this->passwordResetValidator->validateModification([
            'password'     => $password,
            'confirmation' => $confirmation,
        ]);

        if (!$valid) {
            foreach ($errors as $error_list) {
                foreach ($error_list as $error) {
                    $output->writeln('<error>'.$error.'</error>');
                }
            }
        }

        return $valid;
    }

    /**
     * Reset the password.
     *
     * @param OutputInterface $output
     * @param string          $username
     * @param string          $password
     *
     * @return bool
     */
    private function resetPassword(OutputInterface $output, $username, $password)
    {
        $userId = $this->userModel->getIdByUsername($username);

        if (empty($userId)) {
            $output->writeln('<error>User not found</error>');

            return false;
        }

        if (!$this->userModel->update(['id' => $userId, 'password' => $password])) {
            $output->writeln('<error>Unable to update password</error>');

            return false;
        }

        $output->writeln('<info>Password updated successfully</info>');

        return true;
    }
}
