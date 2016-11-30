<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hiject\Core\Plugin;

use PDOException;
use RuntimeException;

/**
 * Class SchemaHandler.
 */
class SchemaHandler extends \Hiject\Core\Base
{
    /**
     * Schema version table for plugins.
     *
     * @var string
     */
    const TABLE_SCHEMA = 'plugin_schema_versions';

    /**
     * Get schema filename.
     *
     * @static
     *
     * @param string $pluginName
     *
     * @return string
     */
    public static function getSchemaFilename($pluginName)
    {
        return PLUGINS_DIR.'/'.$pluginName.'/Schema/'.ucfirst(DB_DRIVER).'.php';
    }

    /**
     * Return true if the plugin has schema.
     *
     * @static
     *
     * @param string $pluginName
     *
     * @return bool
     */
    public static function hasSchema($pluginName)
    {
        return file_exists(self::getSchemaFilename($pluginName));
    }

    /**
     * Load plugin schema.
     *
     * @param string $pluginName
     */
    public function loadSchema($pluginName)
    {
        require_once self::getSchemaFilename($pluginName);
        $this->migrateSchema($pluginName);
    }

    /**
     * Execute plugin schema migrations.
     *
     * @param string $pluginName
     */
    public function migrateSchema($pluginName)
    {
        $lastVersion = constant('\Hiject\Plugin\\'.$pluginName.'\Schema\VERSION');
        $currentVersion = $this->getSchemaVersion($pluginName);

        try {
            $this->db->startTransaction();
            $this->db->getDriver()->disableForeignKeys();

            for ($i = $currentVersion + 1; $i <= $lastVersion; $i++) {
                $functionName = '\Hiject\Plugin\\'.$pluginName.'\Schema\version_'.$i;

                if (function_exists($functionName)) {
                    call_user_func($functionName, $this->db->getConnection());
                }
            }

            $this->db->getDriver()->enableForeignKeys();
            $this->db->closeTransaction();
            $this->setSchemaVersion($pluginName, $i - 1);
        } catch (PDOException $e) {
            $this->db->cancelTransaction();
            $this->db->getDriver()->enableForeignKeys();
            throw new RuntimeException('Unable to migrate schema for the plugin: '.$pluginName.' => '.$e->getMessage());
        }
    }

    /**
     * Get current plugin schema version.
     *
     * @param string $plugin
     *
     * @return int
     */
    public function getSchemaVersion($plugin)
    {
        return (int) $this->db->table(self::TABLE_SCHEMA)->eq('plugin', strtolower($plugin))->findOneColumn('version');
    }

    /**
     * Save last plugin schema version.
     *
     * @param string $plugin
     * @param int    $version
     *
     * @return bool
     */
    public function setSchemaVersion($plugin, $version)
    {
        $dictionary = [
            strtolower($plugin) => $version,
        ];

        return $this->db->getDriver()->upsert(self::TABLE_SCHEMA, 'plugin', 'version', $dictionary);
    }
}
