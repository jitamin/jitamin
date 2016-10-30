<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Formatter;

use Hiject\Core\Base;
use PicoDb\Table;
use Pimple\Container;

/**
 * Class BaseFormatter
 */
abstract class BaseFormatter extends Base
{
    /**
     * Query object
     *
     * @access protected
     * @var Table
     */
    protected $query;

    /**
     * Get object instance
     *
     * @static
     * @access public
     * @param  Container $container
     * @return static
     */
    public static function getInstance(Container $container)
    {
        return new static($container);
    }

    /**
     * Set query
     *
     * @access public
     * @param  Table $query
     * @return $this
     */
    public function withQuery(Table $query)
    {
        $this->query = $query;
        return $this;
    }
}
