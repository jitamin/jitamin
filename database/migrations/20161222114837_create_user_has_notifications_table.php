<?php

use Phinx\Migration\AbstractMigration;

class CreateUserHasNotificationsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('user_has_notifications');
        $table->addColumn('user_id', 'integer')
              ->addColumn('project_id', 'integer')
              ->addIndex(['user_id', 'project_id'], ['unique' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
