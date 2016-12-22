<?php

use Phinx\Migration\AbstractMigration;

class CreatePasswordResetTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('password_reset');
        $table->addColumn('token', 'string', ['limit' => 80])
              ->addColumn('user_id','integer')
              ->addColumn('date_expiration','integer')
              ->addColumn('date_creation','integer')
              ->addColumn('ip', 'string', ['limit' => 45])
              ->addColumn('user_agent', 'string')
              ->addColumn('is_active','boolean')
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
