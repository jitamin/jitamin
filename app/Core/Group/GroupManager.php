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
 * Group Manager.
 */
class GroupManager
{
    /**
     * List of backend providers.
     *
     * @var array
     */
    private $providers = [];

    /**
     * Register a new group backend provider.
     *
     * @param GroupBackendProviderInterface $provider
     *
     * @return GroupManager
     */
    public function register(GroupBackendProviderInterface $provider)
    {
        $this->providers[] = $provider;

        return $this;
    }

    /**
     * Find a group from a search query.
     *
     * @param string $input
     *
     * @return GroupProviderInterface[]
     */
    public function find($input)
    {
        $groups = [];

        foreach ($this->providers as $provider) {
            $groups = array_merge($groups, $provider->find($input));
        }

        return $this->removeDuplicates($groups);
    }

    /**
     * Remove duplicated groups.
     *
     * @param array $groups
     *
     * @return GroupProviderInterface[]
     */
    private function removeDuplicates(array $groups)
    {
        $result = [];

        foreach ($groups as $group) {
            if (!isset($result[$group->getName()])) {
                $result[$group->getName()] = $group;
            }
        }

        return array_values($result);
    }
}
