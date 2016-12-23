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

class CreateTasksTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('tasks');
        $table->addColumn('title', 'string')
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('date_creation', 'biginteger', ['null' => true])
              ->addColumn('date_completed', 'biginteger', ['null' => true])
              ->addColumn('date_due', 'biginteger', ['null' => true])
              ->addColumn('color_id', 'string', ['null' => true, 'limit' => 50])
              ->addColumn('project_id', 'integer')
              ->addColumn('column_id', 'integer')
              ->addColumn('owner_id', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('position', 'integer', ['null' => true])
              ->addColumn('score', 'integer', ['null' => true])
              ->addColumn('is_active', 'boolean', ['null' => true, 'default' => true])
              ->addColumn('category_id', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('creator_id', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('date_modification', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('reference', 'string', ['null' => true, 'limit' => 50, 'default' => ''])
              ->addColumn('date_started', 'biginteger', ['null' => true])
              ->addColumn('time_spent', 'float', ['null' => true, 'default' => 0])
              ->addColumn('time_estimated', 'float', ['null' => true, 'default' => 0])
              ->addColumn('swimlane_id', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('date_moved', 'biginteger', ['null' => true])
              ->addColumn('recurrence_status', 'integer', ['default' => 0])
              ->addColumn('recurrence_trigger', 'integer', ['default' => 0])
              ->addColumn('recurrence_factor', 'integer', ['default' => 0])
              ->addColumn('recurrence_timeframe', 'integer', ['default' => 0])
              ->addColumn('recurrence_basedate', 'integer', ['default' => 0])
              ->addColumn('recurrence_parent', 'integer', ['null' => true])
              ->addColumn('recurrence_child', 'integer', ['null' => true])
              ->addColumn('priority', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('progress', 'integer', ['null' => true, 'default' => 0])
              ->addIndex(['is_active'])
              ->addIndex(['reference'])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('column_id', 'columns', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
