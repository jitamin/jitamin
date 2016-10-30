<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Group;

/**
 * Group Provider Interface
 */
interface GroupProviderInterface
{
    /**
     * Get internal id
     *
     * You must return 0 if the group come from an external backend
     *
     * @access public
     * @return integer
     */
    public function getInternalId();

    /**
     * Get external id
     *
     * You must return a unique id if the group come from an external provider
     *
     * @access public
     * @return string
     */
    public function getExternalId();

    /**
     * Get group name
     *
     * @access public
     * @return string
     */
    public function getName();
}
