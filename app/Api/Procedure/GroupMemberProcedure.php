<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

/**
 * Group Member API controller.
 */
class GroupMemberProcedure extends BaseProcedure
{
    /**
     * Get all groups for a given user.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getMemberGroups($user_id)
    {
        return $this->groupMemberModel->getGroups($user_id);
    }

    /**
     * Get all users.
     *
     * @param int $group_id
     *
     * @return array
     */
    public function getGroupMembers($group_id)
    {
        return $this->groupMemberModel->getMembers($group_id);
    }

    /**
     * Add user to a group.
     *
     * @param int $group_id
     * @param int $user_id
     *
     * @return bool
     */
    public function addGroupMember($group_id, $user_id)
    {
        return $this->groupMemberModel->addUser($group_id, $user_id);
    }

    /**
     * Remove user from a group.
     *
     * @param int $group_id
     * @param int $user_id
     *
     * @return bool
     */
    public function removeGroupMember($group_id, $user_id)
    {
        return $this->groupMemberModel->removeUser($group_id, $user_id);
    }

    /**
     * Check if a user is member.
     *
     * @param int $group_id
     * @param int $user_id
     *
     * @return bool
     */
    public function isGroupMember($group_id, $user_id)
    {
        return $this->groupMemberModel->isMember($group_id, $user_id);
    }
}
