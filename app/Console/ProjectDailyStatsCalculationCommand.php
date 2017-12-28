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

use Jitamin\Model\ProjectModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * project daily stats calculation command class.
 */
class ProjectDailyStatsCalculationCommand extends BaseCommand
{
    /**
     * Configure the console command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('projects:daily-stats')
            ->setDescription('Calculate daily statistics for all projects');
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projects = $this->projectModel->getAllByStatus(ProjectModel::ACTIVE);

        foreach ($projects as $project) {
            $output->writeln('Run calculation for '.$project['name']);
            $this->projectDailyColumnStatsModel->updateTotals($project['id'], date('Y-m-d'));
            $this->projectDailyStatsModel->updateTotals($project['id'], date('Y-m-d'));
        }
    }
}
