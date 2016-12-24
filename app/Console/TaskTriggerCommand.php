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

use Jitamin\Bus\Event\TaskListEvent;
use Jitamin\Model\TaskModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Task trigger command class.
 */
class TaskTriggerCommand extends BaseCommand
{
    /**
     * Configure the console command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('trigger:tasks')
            ->setDescription('Trigger scheduler event for all tasks');
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
        foreach ($this->getProjectIds() as $project_id) {
            $tasks = $this->taskFinderModel->getAll($project_id);
            $nb_tasks = count($tasks);

            if ($nb_tasks > 0) {
                $output->writeln('Trigger task event: project_id='.$project_id.', nb_tasks='.$nb_tasks);
                $this->sendEvent($tasks, $project_id);
            }
        }
    }

    /**
     * Get the project ids.
     *
     * @return int[]
     */
    private function getProjectIds()
    {
        $listeners = $this->dispatcher->getListeners(TaskModel::EVENT_DAILY_CRONJOB);
        $project_ids = [];

        foreach ($listeners as $listener) {
            $project_ids[] = $listener[0]->getProjectId();
        }

        return array_unique($project_ids);
    }

    /**
     * Send the event.
     *
     * @param array $tasks
     * @param int   $project_id
     *
     * @return void
     */
    private function sendEvent(array &$tasks, $project_id)
    {
        $event = new TaskListEvent(['project_id' => $project_id]);
        $event->setTasks($tasks);

        $this->dispatcher->dispatch(TaskModel::EVENT_DAILY_CRONJOB, $event);
    }
}
