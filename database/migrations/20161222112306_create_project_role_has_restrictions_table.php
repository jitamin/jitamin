<?php

use Phinx\Migration\AbstractMigration;

class CreateProjectRoleHasRestrictionsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_role_has_restrictions', ['id' => 'restriction_id']);
        $table->addColumn('project_id','integer')
              ->addColumn('role_id', 'integer')
              ->addColumn('rule', 'string')
              ->addIndex(['role_id', 'rule'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('role_id', 'project_has_roles', 'role_id', ['delete' => 'CASCADE'])
              ->create();
    }
}
