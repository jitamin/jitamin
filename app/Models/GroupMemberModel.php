<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Core\Database\Model;

/**
 * Group Member Model.
 */
class GroupMemberModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'group_has_users';

    /**
     * Get query to fetch all users.
     *
     * @param int $group_id
     *
     * @return \PicoDb\Table
     */
    public function getQuery($group_id)
    {
        return $this->db->table(self::TABLE)
            ->join(UserModel::TABLE, 'id', 'user_id')
            ->eq('group_id', $group_id);
    }

    /**
     * Get all users.
     *
     * @param int $group_id
     *
     * @return array
     */
    public function getMembers($group_id)
    {
        return $this->getQuery($group_id)->findAll();
    }

    /**
     * Get all not members.
     *
     * @param int $group_id
     *
     * @return array
     */
    public function getNotMembers($group_id)
    {
        $subquery = $this->db->table(self::TABLE)
            ->columns('user_id')
            ->eq('group_id', $group_id);

        return $this->db->table(UserModel::TABLE)
            ->notInSubquery('id', $subquery)
            ->findAll();
    }

    /**
     * Add user to a group.
     *
     * @param int $group_id
     * @param int $user_id
     *
     * @return bool
     */
    public function addUser($group_id, $user_id)
    {
        return $this->db->table(self::TABLE)->insert([
            'group_id' => $group_id,
            'user_id'  => $user_id,
        ]);
    }

    /**
     * Remove user from a group.
     *
     * @param int $group_id
     * @param int $user_id
     *
     * @return bool
     */
    public function removeUser($group_id, $user_id)
    {
        return $this->db->table(self::TABLE)
            ->eq('group_id', $group_id)
            ->eq('user_id', $user_id)
            ->remove();
    }

    /**
     * Check if a user is member.
     *
     * @param int $group_id
     * @param int $user_id
     *
     * @return bool
     */
    public function isMember($group_id, $user_id)
    {
        return $this->db->table(self::TABLE)
            ->eq('group_id', $group_id)
            ->eq('user_id', $user_id)
            ->exists();
    }

    /**
     * Get all groups for a given user.
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getGroups($user_id)
    {
        return $this->db->table(self::TABLE)
            ->columns(GroupModel::TABLE.'.id', GroupModel::TABLE.'.external_id', GroupModel::TABLE.'.name')
            ->join(GroupModel::TABLE, 'id', 'group_id')
            ->eq(self::TABLE.'.user_id', $user_id)
            ->asc(GroupModel::TABLE.'.name')
            ->findAll();
    }
}
