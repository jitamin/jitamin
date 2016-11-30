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
 * Application Settings.
 */
abstract class SettingModel extends Base
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'settings';

    /**
     * Prepare data before save.
     *
     * @abstract
     *
     * @param array $values
     *
     * @return array
     */
    abstract public function prepare(array $values);

    /**
     * Get all settings.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->db->hashtable(self::TABLE)->getAll('option', 'value');
    }

    /**
     * Get a setting value.
     *
     * @param string $name
     * @param string $default
     *
     * @return mixed
     */
    public function getOption($name, $default = '')
    {
        $value = $this->db
            ->table(self::TABLE)
            ->eq('option', $name)
            ->findOneColumn('value');

        return $value === null || $value === false || $value === '' ? $default : $value;
    }

    /**
     * Return true if a setting exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function exists($name)
    {
        return $this->db
            ->table(self::TABLE)
            ->eq('option', $name)
            ->exists();
    }

    /**
     * Update or insert new settings.
     *
     * @param array $values
     *
     * @return bool
     */
    public function save(array $values)
    {
        $results = [];
        $values = $this->prepare($values);
        $user_id = $this->userSession->getId();
        $timestamp = time();

        $this->db->startTransaction();

        foreach ($values as $option => $value) {
            if ($this->exists($option)) {
                $results[] = $this->db->table(self::TABLE)->eq('option', $option)->update([
                    'value'      => $value,
                    'changed_on' => $timestamp,
                    'changed_by' => $user_id,
                ]);
            } else {
                $results[] = $this->db->table(self::TABLE)->insert([
                    'option'     => $option,
                    'value'      => $value,
                    'changed_on' => $timestamp,
                    'changed_by' => $user_id,
                ]);
            }
        }

        $this->db->closeTransaction();

        return !in_array(false, $results, true);
    }
}
