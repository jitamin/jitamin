<?php

use Phinx\Migration\AbstractMigration;

class CreateColumnHasMoveRestrictionsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('column_has_move_restrictions', ['id' => 'restriction_id']);
        $table->addColumn('project_id','integer')
              ->addColumn('role_id', 'integer')
              ->addColumn('src_column_id', 'integer')
              ->addColumn('dst_column_id', 'integer')
              ->addIndex(['role_id', 'src_column_id', 'dst_column_id'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('role_id', 'project_has_roles', 'role_id', ['delete' => 'CASCADE'])
              ->addForeignKey('src_column_id', 'columns', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('dst_column_id', 'columns', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
