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

class AlterTableActionsAddPositionColumn extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('actions');
        $table->addColumn('position', 'integer', ['null' => true, 'default' => 0, 'after' => 'action_name'])
              ->update();
    }
}
