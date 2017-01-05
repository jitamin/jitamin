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

class CreateUserHasUnreadNotificationsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('user_has_unread_notifications');
        $table->addColumn('user_id', 'integer')
              ->addColumn('date_creation', 'biginteger')
              ->addColumn('event_name', 'string', ['limit' => 50])
              ->addColumn('event_data', 'text')
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
