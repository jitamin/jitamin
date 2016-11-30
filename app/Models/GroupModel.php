<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Model;

use Hiject\Core\Base;

/**
 * Group Model.
 */
class GroupModel extends Base
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'groups';

    /**
     * Get query to fetch all groups.
     *
     * @return \PicoDb\Table
     */
    public function getQuery()
    {
        return $this->db->table(self::TABLE);
    }

    /**
     * Get a specific group by id.
     *
     * @param int $group_id
     *
     * @return array
     */
    public function getById($group_id)
    {
        return $this->getQuery()->eq('id', $group_id)->findOne();
    }

    /**
     * Get a specific group by external id.
     *
     * @param int $external_id
     *
     * @return array
     */
    public function getByExternalId($external_id)
    {
        return $this->getQuery()->eq('external_id', $external_id)->findOne();
    }

    /**
     * Get all groups.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->getQuery()->asc('name')->findAll();
    }

    /**
     * Search groups by name.
     *
     * @param string $input
     *
     * @return array
     */
    public function search($input)
    {
        return $this->db->table(self::TABLE)->ilike('name', '%'.$input.'%')->asc('name')->findAll();
    }

    /**
     * Remove a group.
     *
     * @param int $group_id
     *
     * @return bool
     */
    public function remove($group_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $group_id)->remove();
    }

    /**
     * Create a new group.
     *
     * @param string $name
     * @param string $external_id
     *
     * @return int|bool
     */
    public function create($name, $external_id = '')
    {
        return $this->db->table(self::TABLE)->persist([
            'name'        => $name,
            'external_id' => $external_id,
        ]);
    }

    /**
     * Update existing group.
     *
     * @param array $values
     *
     * @return bool
     */
    public function update(array $values)
    {
        return $this->db->table(self::TABLE)->eq('id', $values['id'])->update($values);
    }

    /**
     * Get groupId from externalGroupId and create the group if not found.
     *
     * @param string $name
     * @param string $external_id
     *
     * @return bool|int
     */
    public function getOrCreateExternalGroupId($name, $external_id)
    {
        $group_id = $this->db->table(self::TABLE)->eq('external_id', $external_id)->findOneColumn('id');

        if (empty($group_id)) {
            $group_id = $this->create($name, $external_id);
        }

        return $group_id;
    }
}
