<?php

use Phinx\Migration\AbstractMigration;

class CreateLastLoginsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('last_logins');
        $table->addColumn('auth_type', 'string', ['null' => true, 'limit' => 25])
              ->addColumn('user_id','integer', ['null' => true])
              ->addColumn('ip', 'string', ['null'=> true, 'limit' => 45])
              ->addColumn('user_agent', 'string', ['null'=> true])
              ->addColumn('date_creation','biginteger',['null' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
