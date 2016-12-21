<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Group;

/**
 * Group Provider Interface.
 */
interface GroupProviderInterface
{
    /**
     * Get internal id.
     *
     * You must return 0 if the group come from an external backend
     *
     * @return int
     */
    public function getInternalId();

    /**
     * Get external id.
     *
     * You must return a unique id if the group come from an external provider
     *
     * @return string
     */
    public function getExternalId();

    /**
     * Get group name.
     *
     * @return string
     */
    public function getName();
}
