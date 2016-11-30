<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WorkerCommand.
 */
class WorkerCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('worker')
            ->setDescription('Execute queue worker');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->queueManager->listen();
    }
}
