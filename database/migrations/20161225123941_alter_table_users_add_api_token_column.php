<?php

use Phinx\Migration\AbstractMigration;

class AlterTableUsersAddApiTokenColumn extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('api_token', 'string', ['null' => true, 'after' => 'token'])
              ->update();
    }
}
