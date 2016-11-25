<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Bus\Job;

use Hiject\Core\Base;

/**
 * Class BaseJob
 */
abstract class BaseJob extends Base
{
    /**
     * Job parameters
     *
     * @access protected
     * @var array
     */
    protected $jobParams = [];

    /**
     * Get job parameters
     *
     * @access public
     * @return array
     */
    public function getJobParams()
    {
        return $this->jobParams;
    }
}
