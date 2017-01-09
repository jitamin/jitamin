<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Formatter;

use Jitamin\Foundation\Filter\FormatterInterface;

/**
 * Common class to handle calendar events.
 */
abstract class BaseTaskCalendarFormatter extends BaseFormatter
{
    /**
     * Column used for event start date.
     *
     * @var string
     */
    protected $startColumn = 'date_started';

    /**
     * Column used for event end date.
     *
     * @var string
     */
    protected $endColumn = 'date_completed';

    /**
     * Transform results to calendar events.
     *
     * @param string $start_column Column name for the start date
     * @param string $end_column   Column name for the end date
     *
     * @return FormatterInterface
     */
    public function setColumns($start_column, $end_column = '')
    {
        $this->startColumn = $start_column;
        $this->endColumn = $end_column ?: $start_column;

        return $this;
    }
}
