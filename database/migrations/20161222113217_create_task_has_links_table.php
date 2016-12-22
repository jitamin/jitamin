<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Phinx\Migration\AbstractMigration;

class CreateTaskHasLinksTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_has_links', ['id' => false]);
        $table->addColumn('link_id', 'integer')
              ->addColumn('task_id', 'integer')
              ->addColumn('opposite_task_id', 'integer')
              ->addIndex(['link_id', 'task_id', 'opposite_task_id'], ['unique' => true])
              ->addForeignKey('link_id', 'links', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('task_id', 'tasks', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('opposite_task_id', 'tasks', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
