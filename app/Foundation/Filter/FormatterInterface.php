<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Filter;

use PicoDb\Table;

/**
 * Formatter interface.
 */
interface FormatterInterface
{
    /**
     * Set query.
     *
     * @param Table $query
     *
     * @return FormatterInterface
     */
    public function withQuery(Table $query);

    /**
     * Apply formatter.
     *
     * @return mixed
     */
    public function format();
}
