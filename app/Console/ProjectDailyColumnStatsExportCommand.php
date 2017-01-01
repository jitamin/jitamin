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

use Jitamin\Core\Csv;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * project daily column stats export command class.
 */
class ProjectDailyColumnStatsExportCommand extends BaseCommand
{
    /**
     * Configure the console command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('export:daily-project-column-stats')
            ->setDescription('Daily project column stats CSV export (number of tasks per column and per day)')
            ->addArgument('project_id', InputArgument::REQUIRED, 'Project id')
            ->addArgument('start_date', InputArgument::REQUIRED, 'Start date (YYYY-MM-DD)')
            ->addArgument('end_date', InputArgument::REQUIRED, 'End date (YYYY-MM-DD)');
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
        $data = $this->projectDailyColumnStatsModel->getAggregatedMetrics(
            $input->getArgument('project_id'),
            $input->getArgument('start_date'),
            $input->getArgument('end_date')
        );

        if (is_array($data)) {
            Csv::output($data);
        }
    }
}
