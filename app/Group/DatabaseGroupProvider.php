<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Group;

use Hiject\Core\Group\GroupProviderInterface;

/**
 * Database Group Provider
 */
class DatabaseGroupProvider implements GroupProviderInterface
{
    /**
     * Group properties
     *
     * @access private
     * @var array
     */
    private $group = array();

    /**
     * Constructor
     *
     * @access public
     * @param  array $group
     */
    public function __construct(array $group)
    {
        $this->group = $group;
    }

    /**
     * Get internal id
     *
     * @access public
     * @return integer
     */
    public function getInternalId()
    {
        return (int) $this->group['id'];
    }

    /**
     * Get external id
     *
     * @access public
     * @return string
     */
    public function getExternalId()
    {
        return '';
    }

    /**
     * Get group name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->group['name'];
    }
}
