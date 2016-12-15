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

use Hiject\Core\Database\Model;

/**
 * Task External Link Model.
 */
class TaskExternalLinkModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'task_has_external_links';

    /**
     * Get all links.
     *
     * @param int $task_id
     *
     * @return array
     */
    public function getAll($task_id)
    {
        $types = $this->externalLinkManager->getTypes();

        $links = $this->db->table(self::TABLE)
            ->columns(self::TABLE.'.*', UserModel::TABLE.'.name AS creator_name', UserModel::TABLE.'.username AS creator_username')
            ->eq('task_id', $task_id)
            ->asc('title')
            ->join(UserModel::TABLE, 'id', 'creator_id')
            ->findAll();

        foreach ($links as &$link) {
            $link['dependency_label'] = $this->externalLinkManager->getDependencyLabel($link['link_type'], $link['dependency']);
            $link['type'] = isset($types[$link['link_type']]) ? $types[$link['link_type']] : t('Unknown');
        }

        return $links;
    }

    /**
     * Get link.
     *
     * @param int $link_id
     *
     * @return array
     */
    public function getById($link_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $link_id)->findOne();
    }

    /**
     * Add a new link in the database.
     *
     * @param array $values Form values
     *
     * @return bool|int
     */
    public function create(array $values)
    {
        unset($values['id']);
        $values['creator_id'] = $this->userSession->getId();
        $values['date_creation'] = time();
        $values['date_modification'] = $values['date_creation'];

        return $this->db->table(self::TABLE)->persist($values);
    }

    /**
     * Modify external link.
     *
     * @param array $values Form values
     *
     * @return bool
     */
    public function update(array $values)
    {
        $values['date_modification'] = time();

        return $this->db->table(self::TABLE)->eq('id', $values['id'])->update($values);
    }

    /**
     * Remove a link.
     *
     * @param int $link_id
     *
     * @return bool
     */
    public function remove($link_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $link_id)->remove();
    }
}
