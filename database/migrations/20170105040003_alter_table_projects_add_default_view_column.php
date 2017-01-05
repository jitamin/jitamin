<?php

use Phinx\Migration\AbstractMigration;

class AlterTableProjectsAddDefaultViewColumn extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('projects');
        $table->addColumn('default_view', 'string', ['null' => true, 'limit' => 25, 'after' => 'default_swimlane'])
              ->update();
    }
}
