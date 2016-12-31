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

class CreateProjectDailyStatsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_daily_stats');
        $table->addColumn('day', 'string', ['limit' => 10])
              ->addColumn('project_id', 'integer')
              ->addColumn('avg_lead_time', 'integer', ['default' => 0])
              ->addColumn('avg_cycle_time', 'integer', ['default' => 0])
              ->addIndex(['day', 'project_id'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
