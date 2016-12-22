<?php

use Phinx\Migration\AbstractMigration;

class CreateRememberMeTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('remember_me');
        $table->addColumn('user_id','integer', ['null' => true])
              ->addColumn('ip', 'string', ['null' => true, 'limit' => 45])
              ->addColumn('user_agent', 'string', ['null' => true, 'limit' => 255])
              ->addColumn('token', 'string', ['null' => true, 'limit' => 255])
              ->addColumn('sequence', 'string', ['null' => true, 'limit' => 255])
              ->addColumn('expiration','integer', ['null' => true])
              ->addColumn('date_creation','biginteger', ['null' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
