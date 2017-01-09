<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Bus\Job;

use Jitamin\Foundation\Base;

/**
 * Class BaseJob.
 */
abstract class BaseJob extends Base
{
    /**
     * Job parameters.
     *
     * @var array
     */
    protected $jobParams = [];

    /**
     * Get job parameters.
     *
     * @return array
     */
    public function getJobParams()
    {
        return $this->jobParams;
    }
}
