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

class CreateProjectDailyColumnStatsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_daily_column_stats');
        $table->addColumn('day', 'string', ['limit' => 10])
              ->addColumn('project_id', 'integer')
              ->addColumn('column_id', 'integer')
              ->addColumn('total', 'integer', ['default' => 0])
              ->addColumn('score', 'integer', ['default' => 0])
              ->addIndex(['day', 'project_id', 'column_id'], ['unique' => true])
              ->addForeignKey('column_id', 'columns', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
