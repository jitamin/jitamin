<?php

use Phinx\Migration\AbstractMigration;

class CreateSchemaVersionTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('schema_version', ['id' => false]);
        $table->addColumn('version','integer',['null' => true, 'default' => 0])
              ->create();
    }
}
