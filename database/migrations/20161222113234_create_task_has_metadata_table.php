<?php

use Phinx\Migration\AbstractMigration;

class CreateTaskHasMetadataTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('task_has_metadata', ['id' => false]);
        $table->addColumn('task_id', 'integer')
              ->addColumn('name', 'string', ['limit' => 50])
              ->addColumn('value', 'string', ['null' => true, 'default' => ''])
              ->addColumn('changed_by', 'integer', ['default' => 0])
              ->addColumn('changed_on', 'integer', ['default' => 0])
              ->addIndex(['task_id', 'name'], ['unique' => true])
              ->addForeignKey('task_id', 'tasks', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
