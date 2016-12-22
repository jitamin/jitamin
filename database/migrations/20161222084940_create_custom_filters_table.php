<?php

use Phinx\Migration\AbstractMigration;

class CreateCustomFiltersTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('custom_filters');
        $table->addColumn('filter', 'string', ['limit' => 100])
              ->addColumn('project_id','integer')
              ->addColumn('user_id','integer')
              ->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('is_shared','boolean',['null' => true, 'default' => false])
              ->addColumn('append','boolean',['null' => true, 'default' => false])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
