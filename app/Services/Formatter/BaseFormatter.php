<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Formatter;

use Jitamin\Core\Base;
use PicoDb\Table;
use Pimple\Container;

/**
 * Class BaseFormatter.
 */
abstract class BaseFormatter extends Base
{
    /**
     * Query object.
     *
     * @var Table
     */
    protected $query;

    /**
     * Get object instance.
     *
     * @static
     *
     * @param Container $container
     *
     * @return static
     */
    public static function getInstance(Container $container)
    {
        return new static($container);
    }

    /**
     * Set query.
     *
     * @param Table $query
     *
     * @return $this
     */
    public function withQuery(Table $query)
    {
        $this->query = $query;

        return $this;
    }
}
