<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Phinx\Migration\AbstractMigration;

class CreatePluginSchemaVersionsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('plugin_schema_versions', ['id' => false, 'primary_key' => ['plugin']]);
        $table->addColumn('plugin', 'string', ['limit' => 80])
              ->addColumn('version', 'integer', ['default' => 0])
              ->create();
    }
}
