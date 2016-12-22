<?php

use Phinx\Migration\AbstractMigration;

class CreateSubtasksTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('subtasks');
        $table->addColumn('title', 'string')
              ->addColumn('status', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('time_estimated','float', ['null' => true])
              ->addColumn('time_spent','float', ['null' => true])
              ->addColumn('task_id', 'integer')
              ->addColumn('user_id','integer', ['null' => true])
              ->addColumn('position','integer', ['null' => true, 'default' => 1])
              ->addForeignKey('task_id', 'tasks', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
