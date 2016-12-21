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
 * Metadata.
 */
abstract class MetadataModel extends Model
{
    /**
     * Get the table.
     *
     * @abstract
     *
     * @return string
     */
    abstract protected function getTable();

    /**
     * Define the entity key.
     *
     * @abstract
     *
     * @return string
     */
    abstract protected function getEntityKey();

    /**
     * Get all metadata for the entity.
     *
     * @param int $entity_id
     *
     * @return array
     */
    public function getAll($entity_id)
    {
        return $this->db
            ->hashtable($this->getTable())
            ->eq($this->getEntityKey(), $entity_id)
            ->asc('name')
            ->getAll('name', 'value');
    }

    /**
     * Get a metadata for the given entity.
     *
     * @param int    $entity_id
     * @param string $name
     * @param string $default
     *
     * @return mixed
     */
    public function get($entity_id, $name, $default = '')
    {
        return $this->db
            ->table($this->getTable())
            ->eq($this->getEntityKey(), $entity_id)
            ->eq('name', $name)
            ->findOneColumn('value') ?: $default;
    }

    /**
     * Return true if a metadata exists.
     *
     * @param int    $entity_id
     * @param string $name
     *
     * @return bool
     */
    public function exists($entity_id, $name)
    {
        return $this->db
            ->table($this->getTable())
            ->eq($this->getEntityKey(), $entity_id)
            ->eq('name', $name)
            ->exists();
    }

    /**
     * Update or insert new metadata.
     *
     * @param int   $entity_id
     * @param array $values
     *
     * @return bool
     */
    public function save($entity_id, array $values)
    {
        $results = [];
        $user_id = $this->userSession->getId();
        $timestamp = time();

        $this->db->startTransaction();

        foreach ($values as $key => $value) {
            if ($this->exists($entity_id, $key)) {
                $results[] = $this->db->table($this->getTable())
                    ->eq($this->getEntityKey(), $entity_id)
                    ->eq('name', $key)
                    ->update([
                        'value'      => $value,
                        'changed_on' => $timestamp,
                        'changed_by' => $user_id,
                    ]);
            } else {
                $results[] = $this->db->table($this->getTable())->insert([
                    'name'                => $key,
                    'value'               => $value,
                    $this->getEntityKey() => $entity_id,
                    'changed_on'          => $timestamp,
                    'changed_by'          => $user_id,
                ]);
            }
        }

        $this->db->closeTransaction();

        return !in_array(false, $results, true);
    }

    /**
     * Remove a metadata.
     *
     * @param int    $entity_id
     * @param string $name
     *
     * @return bool
     */
    public function remove($entity_id, $name)
    {
        return $this->db->table($this->getTable())
            ->eq($this->getEntityKey(), $entity_id)
            ->eq('name', $name)
            ->remove();
    }
}
