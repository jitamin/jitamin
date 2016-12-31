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

class CreateProjectHasGroupsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_has_groups', ['id' => false]);
        $table->addColumn('group_id', 'integer')
              ->addColumn('project_id', 'integer')
              ->addColumn('role', 'string')
              ->addIndex(['group_id', 'project_id'], ['unique' => true])
              ->addForeignKey('group_id', 'groups', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
