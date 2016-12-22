<?php

use Phinx\Migration\AbstractMigration;

class CreateLinksTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('links');
        $table->addColumn('label','string')
              ->addColumn('opposite_id','integer',['null'=>true, 'default' => 0])
              ->addIndex(['label'], ['unique' => true])
              ->create();
    }
}
