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

use Jitamin\Foundation\Base;
use Jitamin\Foundation\Group\GroupBackendProviderInterface;

/**
 * Database Backend Group Provider.
 */
class DatabaseBackendGroupProvider extends Base implements GroupBackendProviderInterface
{
    /**
     * Find a group from a search query.
     *
     * @param string $input
     *
     * @return DatabaseGroupProvider[]
     */
    public function find($input)
    {
        $result = [];
        $groups = $this->groupModel->search($input);

        foreach ($groups as $group) {
            $result[] = new DatabaseGroupProvider($group);
        }

        return $result;
    }
}
