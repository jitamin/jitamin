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

class CreateTransitionsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('transitions');
        $table->addColumn('user_id', 'integer')
              ->addColumn('project_id', 'integer')
              ->addColumn('task_id', 'integer')
              ->addColumn('src_column_id', 'integer')
              ->addColumn('dst_column_id', 'integer')
              ->addColumn('date', 'biginteger', ['null' => true])
              ->addColumn('time_spent', 'integer', ['null' => true, 'default' => 0])
              ->addForeignKey('src_column_id', 'columns', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('dst_column_id', 'columns', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('task_id', 'tasks', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
