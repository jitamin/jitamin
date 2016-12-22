<?php

use Phinx\Migration\AbstractMigration;

class CreateUserHasNotificationTypesTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('user_has_notification_types');
        $table->addColumn('user_id', 'integer')
              ->addColumn('notification_type', 'string', ['limit' => 50])
              ->addIndex(['user_id', 'notification_type'], ['unique' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
