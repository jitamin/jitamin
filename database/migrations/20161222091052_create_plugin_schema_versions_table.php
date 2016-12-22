<?php

use Phinx\Migration\AbstractMigration;

class CreatePluginSchemaVersionsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('plugin_schema_versions', ['id' => false, 'primary_key' => ['plugin']]);
        $table->addColumn('plugin','string',['limit' => 80])
              ->addColumn('version','integer',['default' => 0])
              ->create();
    }
}
