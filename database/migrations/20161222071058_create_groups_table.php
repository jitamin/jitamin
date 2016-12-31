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

class CreateGroupsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('groups');
        $table->addColumn('external_id', 'string', ['null'=>true, 'default' => ''])
              ->addColumn('name', 'string', ['limit' => 100])
              ->addIndex(['name'], ['unique' => true])
              ->create();
    }
}
