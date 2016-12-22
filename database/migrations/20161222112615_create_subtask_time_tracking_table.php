<?php

use Phinx\Migration\AbstractMigration;

class CreateSubtaskTimeTrackingTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('subtask_time_tracking');
        $table->addColumn('user_id','integer')
              ->addColumn('subtask_id','integer')
              ->addColumn('start','biginteger', ['null'=> true])
              ->addColumn('end','biginteger', ['null'=> true])
              ->addColumn('time_spent','float', ['null'=> true, 'default' => 0])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('subtask_id', 'subtasks', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
