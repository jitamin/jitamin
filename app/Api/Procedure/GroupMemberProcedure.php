<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
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
    public function getMemberGroups($user_id)
    {
        return $this->groupMemberModel->getGroups($user_id);
    }

    public function getGroupMembers($group_id)
    {
        return $this->groupMemberModel->getMembers($group_id);
    }

    public function addGroupMember($group_id, $user_id)
    {
        return $this->groupMemberModel->addUser($group_id, $user_id);
    }

    public function removeGroupMember($group_id, $user_id)
    {
        return $this->groupMemberModel->removeUser($group_id, $user_id);
    }

    public function isGroupMember($group_id, $user_id)
    {
        return $this->groupMemberModel->isMember($group_id, $user_id);
    }
}
