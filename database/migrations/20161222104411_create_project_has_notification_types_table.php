<?php

use Phinx\Migration\AbstractMigration;

class CreateProjectHasNotificationTypesTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_has_notification_types');
        $table->addColumn('project_id', 'integer')
              ->addColumn('notification_type', 'string', ['limit' => 50])
              ->addIndex(['project_id', 'notification_type'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
