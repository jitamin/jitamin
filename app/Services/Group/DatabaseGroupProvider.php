<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Group;

use Jitamin\Foundation\Group\GroupProviderInterface;

/**
 * Database Group Provider.
 */
class DatabaseGroupProvider implements GroupProviderInterface
{
    /**
     * Group properties.
     *
     * @var array
     */
    private $group = [];

    /**
     * Constructor.
     *
     * @param array $group
     */
    public function __construct(array $group)
    {
        $this->group = $group;
    }

    /**
     * Get internal id.
     *
     * @return int
     */
    public function getInternalId()
    {
        return (int) $this->group['id'];
    }

    /**
     * Get external id.
     *
     * @return string
     */
    public function getExternalId()
    {
        return '';
    }

    /**
     * Get group name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->group['name'];
    }
}
