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

class CreateGroupHasUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('group_has_users', ['id'=>false]);
        $table->addColumn('group_id', 'integer')
              ->addColumn('user_id', 'integer')
              ->addIndex(['group_id', 'user_id'], ['unique' => true])
              ->addForeignKey('group_id', 'groups', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
