<?php

use Phinx\Migration\AbstractMigration;

class CreateActionsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('actions');
        $table->addColumn('project_id','integer')
              ->addColumn('event_name', 'string', ['limit' => 50])
              ->addColumn('action_name', 'string', ['null' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
