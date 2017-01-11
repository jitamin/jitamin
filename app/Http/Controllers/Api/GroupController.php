<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Api;

/**
 * Group API controller.
 */
class GroupController extends Controller
{
    /**
     * Create a new group.
     *
     * @param string $name
     * @param string $external_id
     *
     * @return int|bool
     */
    public function createGroup($name, $external_id = '')
    {
        return $this->groupModel->create($name, $external_id);
    }

    /**
     * Update existing group.
     *
     * @param int    $group_id
     * @param string $name
     * @param int    $external_id
     *
     * @return bool
     */
    public function updateGroup($group_id, $name = null, $external_id = null)
    {
        $values = [
            'id'          => $group_id,
            'name'        => $name,
            'external_id' => $external_id,
        ];

        foreach ($values as $key => $value) {
            if (is_null($value)) {
                unset($values[$key]);
            }
        }

        return $this->groupModel->update($values);
    }

    /**
     * Remove a group.
     *
     * @param int $group_id
     *
     * @return bool
     */
    public function removeGroup($group_id)
    {
        return $this->groupModel->remove($group_id);
    }

    /**
     * Get a specific group by id.
     *
     * @param int $group_id
     *
     * @return array
     */
    public function getGroup($group_id)
    {
        return $this->groupModel->getById($group_id);
    }

    /**
     * Get all groups.
     *
     * @return array
     */
    public function getAllGroups()
    {
        return $this->groupModel->getAll();
    }
}
