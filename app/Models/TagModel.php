<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Model;

use Jitamin\Foundation\Database\Model;

/**
 * Class TagModel.
 */
class TagModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'tags';

    /**
     * Get all tags.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->db->table(self::TABLE)->asc('name')->findAll();
    }

    /**
     * Get all tags by project.
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getAllByProject($project_id)
    {
        return $this->db->table(self::TABLE)->eq('project_id', $project_id)->asc('name')->findAll();
    }

    /**
     * Get assignable tags for a project.
     *
     * @param int $project_id
     *
     * @return array
     */
    public function getAssignableList($project_id)
    {
        return $this->db->hashtable(self::TABLE)
            ->beginOr()
            ->eq('project_id', $project_id)
            ->eq('project_id', 0)
            ->closeOr()
            ->asc('name')
            ->getAll('id', 'name');
    }

    /**
     * Get one tag.
     *
     * @param int $tag_id
     *
     * @return array|null
     */
    public function getById($tag_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $tag_id)->findOne();
    }

    /**
     * Get tag id from tag name.
     *
     * @param int    $project_id
     * @param string $tag
     *
     * @return int
     */
    public function getIdByName($project_id, $tag)
    {
        return $this->db
            ->table(self::TABLE)
            ->beginOr()
                ->eq('project_id', 0)
                ->eq('project_id', $project_id)
            ->closeOr()
            ->ilike('name', $tag)
            ->asc('project_id')
            ->findOneColumn('id');
    }

    /**
     * Return true if the tag exists.
     *
     * @param int    $project_id
     * @param string $tag
     * @param int    $tag_id
     *
     * @return bool
     */
    public function exists($project_id, $tag, $tag_id = 0)
    {
        return $this->db
            ->table(self::TABLE)
            ->neq('id', $tag_id)
            ->beginOr()
            ->eq('project_id', 0)
            ->eq('project_id', $project_id)
            ->closeOr()
            ->ilike('name', $tag)
            ->asc('project_id')
            ->exists();
    }

    /**
     * Return tag id and create a new tag if necessary.
     *
     * @param int    $project_id
     * @param string $tag
     *
     * @return bool|int
     */
    public function findOrCreateTag($project_id, $tag)
    {
        $tag_id = $this->getIdByName($project_id, $tag);

        if (empty($tag_id)) {
            $tag_id = $this->create($project_id, $tag);
        }

        return $tag_id;
    }

    /**
     * Add a new tag.
     *
     * @param int    $project_id
     * @param string $tag
     *
     * @return bool|int
     */
    public function create($project_id, $tag)
    {
        return $this->db->table(self::TABLE)->persist([
            'project_id' => $project_id,
            'name'       => $tag,
        ]);
    }

    /**
     * Update a tag.
     *
     * @param int    $tag_id
     * @param string $tag
     *
     * @return bool
     */
    public function update($tag_id, $tag)
    {
        return $this->db->table(self::TABLE)->eq('id', $tag_id)->update([
            'name' => $tag,
        ]);
    }

    /**
     * Remove a tag.
     *
     * @param int $tag_id
     *
     * @return bool
     */
    public function remove($tag_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $tag_id)->remove();
    }
}
