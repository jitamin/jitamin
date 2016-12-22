<?php

use Phinx\Migration\AbstractMigration;

class CreateProjectHasRolesTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_has_roles', ['id' => 'role_id']);
        $table->addColumn('role', 'string')
              ->addColumn('project_id','integer')
              ->addIndex(['project_id', 'role'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
