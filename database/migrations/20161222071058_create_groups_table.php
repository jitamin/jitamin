<?php

use Phinx\Migration\AbstractMigration;

class CreateGroupsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('groups');
        $table->addColumn('external_id','string',['null'=>true, 'default' => ''])
              ->addColumn('name','string',['limit' => 100])
              ->addIndex(['name'], ['unique' => true])
              ->create();
    }
}
