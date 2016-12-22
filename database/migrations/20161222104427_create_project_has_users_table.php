<?php

use Phinx\Migration\AbstractMigration;

class CreateProjectHasUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_has_users', ['id' => false]);
        $table->addColumn('project_id', 'integer')
              ->addColumn('user_id', 'integer')
              ->addColumn('role', 'string')
              ->addIndex(['project_id', 'user_id'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
