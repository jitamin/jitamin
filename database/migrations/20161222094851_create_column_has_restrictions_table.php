<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Phinx\Migration\AbstractMigration;

class CreateColumnHasRestrictionsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('column_has_restrictions', ['id' => 'restriction_id']);
        $table->addColumn('project_id', 'integer')
              ->addColumn('role_id', 'integer')
              ->addColumn('column_id', 'integer')
              ->addColumn('rule', 'string')
              ->addIndex(['role_id', 'column_id', 'rule'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('role_id', 'project_has_roles', 'role_id', ['delete' => 'CASCADE'])
              ->addForeignKey('column_id', 'columns', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
