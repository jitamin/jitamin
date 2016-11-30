<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Filter;

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
