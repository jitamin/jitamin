<?php

use Phinx\Migration\AbstractMigration;

class CreateProjectActivitiesTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_activities');
        $table->addColumn('date_creation', 'biginteger', ['null' => true])
              ->addColumn('event_name', 'string', ['limit' => 50])
              ->addColumn('creator_id','integer', ['null' => true])
              ->addColumn('project_id','integer', ['null' => true])
              ->addColumn('task_id','integer', ['null' => true])
              ->addColumn('data', 'text', ['null' => true])
              ->addForeignKey('creator_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('task_id', 'tasks', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
