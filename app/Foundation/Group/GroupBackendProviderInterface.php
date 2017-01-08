<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core\Group;

/**
 * Group Backend Provider Interface.
 */
interface GroupBackendProviderInterface
{
    /**
     * Find a group from a search query.
     *
     * @param string $input
     *
     * @return GroupProviderInterface[]
     */
    public function find($input);
}
