<?php

use Phinx\Migration\AbstractMigration;

class CreateTagsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('tags');
        $table->addColumn('name','string')
              ->addColumn('project_id', 'integer')
              ->addIndex(['name', 'project_id'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
