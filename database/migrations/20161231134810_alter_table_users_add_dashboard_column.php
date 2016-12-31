<?php

use Phinx\Migration\AbstractMigration;

class AlterTableUsersAddDashboardColumn extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('dashboard', 'string', ['null' => true, 'limit' => '25', 'after' => 'layout'])
              ->update();
    }
}
