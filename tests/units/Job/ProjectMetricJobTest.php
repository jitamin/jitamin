<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hiject\Bus\Job\ProjectMetricJob;

require_once __DIR__.'/../Base.php';

class ProjectMetricJobTest extends Base
{
    public function testJobParams()
    {
        $projectMetricJob = new ProjectMetricJob($this->container);
        $projectMetricJob->withParams(123);

        $this->assertSame(
            array(123),
            $projectMetricJob->getJobParams()
        );
    }

    public function testJob()
    {
        $this->container['projectDailyColumnStatsModel'] = $this
            ->getMockBuilder('\Hiject\Model\ProjectDailyColumnStatsModel')
            ->setConstructorArgs(array($this->container))
            ->setMethods(array('updateTotals'))
            ->getMock();

        $this->container['projectDailyStatsModel'] = $this
            ->getMockBuilder('\Hiject\Model\ProjectDailyStatsModel')
            ->setConstructorArgs(array($this->container))
            ->setMethods(array('updateTotals'))
            ->getMock();

        $this->container['projectDailyColumnStatsModel']
            ->expects($this->once())
            ->method('updateTotals')
            ->with(42, date('Y-m-d'));

        $this->container['projectDailyStatsModel']
            ->expects($this->once())
            ->method('updateTotals')
            ->with(42, date('Y-m-d'));

        $job = new ProjectMetricJob($this->container);
        $job->execute(42);
    }
}
