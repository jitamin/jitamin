<?php

use Phinx\Migration\AbstractMigration;

class CreateProjectHasMetadataTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_has_metadata', ['id' => false]);
        $table->addColumn('project_id', 'integer')
              ->addColumn('name', 'string', ['limit' => 50])
              ->addColumn('value', 'string', ['null' => true, 'default' => ''])
              ->addColumn('changed_by', 'integer', ['default' => 0])
              ->addColumn('changed_on', 'integer', ['default' => 0])
              ->addIndex(['project_id', 'name'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
