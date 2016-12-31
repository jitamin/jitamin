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

use Jitamin\Core\Database\Model;
use Jitamin\Core\Security\Token;

/**
 * Setting model.
 */
class SettingModel extends Model
{
    /**
     * SQL table name.
     *
     * @var string
     */
    const TABLE = 'settings';

    /**
     * Get a config variable with in-memory caching.
     *
     * @param string $name          Parameter name
     * @param string $default_value Default value of the parameter
     *
     * @return string
     */
    public function get($name, $default_value = '')
    {
        $options = $this->memoryCache->proxy($this, 'getAll');

        return isset($options[$name]) && $options[$name] !== '' ? $options[$name] : $default_value;
    }

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

    /**
     * Optimize the Sqlite database.
     *
     * @return bool
     */
    public function optimizeDatabase()
    {
        return $this->db->getConnection()->exec('VACUUM');
    }

    /**
     * Compress the Sqlite database.
     *
     * @return string
     */
    public function downloadDatabase()
    {
        return gzencode(file_get_contents(DB_FILENAME));
    }

    /**
     * Get the Sqlite database size in bytes.
     *
     * @return int
     */
    public function getDatabaseSize()
    {
        return DB_DRIVER === 'sqlite' ? filesize(DB_FILENAME) : 0;
    }

    /**
     * Regenerate a token.
     *
     * @param string $option Parameter name
     *
     * @return bool
     */
    public function regenerateToken($option)
    {
        return $this->save([$option => Token::getToken()]);
    }

    /**
     * Prepare data before save.
     *
     * @param array $values
     *
     * @return array
     */
    public function prepare(array $values)
    {
        if (!empty($values['application_url']) && substr($values['application_url'], -1) !== '/') {
            $values['application_url'] = $values['application_url'].'/';
        }

        return $values;
    }
}
