<?php

use Phinx\Migration\AbstractMigration;

class CreateActionHasParamsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('action_has_params');
        $table->addColumn('action_id','integer')
              ->addColumn('name', 'string', ['limit' => 50])
              ->addColumn('value', 'string', ['limit'=> 50])
              ->addForeignKey('action_id', 'actions', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
