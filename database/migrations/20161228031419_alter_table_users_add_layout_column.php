<?php

use Phinx\Migration\AbstractMigration;

class AlterTableUsersAddLayoutColumn extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('layout', 'string', ['null' => true, 'limit' => '15', 'after' => 'skin'])
              ->update();
    }
}
