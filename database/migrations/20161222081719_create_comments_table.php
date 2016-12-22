<?php

use Phinx\Migration\AbstractMigration;

class CreateCommentsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('comments');
        $table->addColumn('task_id', 'integer')
              ->addColumn('user_id','integer', ['null' => true, 'default' => 0])
              ->addColumn('date_creation','biginteger', ['null' => true])
              ->addColumn('comment', 'text', ['null' => true])
              ->addColumn('reference','string', ['null' => true, 'limit' => 50, 'default' => ''])
              ->addIndex(['user_id'])
              ->addIndex(['reference'])
              ->addForeignKey('task_id', 'tasks', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
