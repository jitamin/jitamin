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

class AlterTableProjectsAddDefaultViewColumn extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('projects');
        $table->addColumn('default_view', 'string', ['null' => true, 'limit' => 25, 'after' => 'default_swimlane'])
              ->update();
    }
}
