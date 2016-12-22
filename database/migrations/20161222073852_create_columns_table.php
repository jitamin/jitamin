<?php

use Phinx\Migration\AbstractMigration;

class CreateColumnsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('columns');
        $table->addColumn('title', 'string')
              ->addColumn('position','integer')
              ->addColumn('project_id','integer')
              ->addColumn('task_limit','integer', ['null' => true, 'default' => 0])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('hide_in_dashboard','integer', ['default' => 0])
              ->addIndex(['title', 'project_id'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
