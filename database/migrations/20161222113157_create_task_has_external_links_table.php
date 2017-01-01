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

class CreateTaskHasExternalLinksTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('task_has_external_links');
        $table->addColumn('link_type', 'string', ['limit' => 100])
              ->addColumn('dependency', 'string', ['limit' => 100])
              ->addColumn('title', 'string')
              ->addColumn('url', 'string')
              ->addColumn('date_creation', 'integer')
              ->addColumn('date_modification', 'integer')
              ->addColumn('task_id', 'integer')
              ->addColumn('creator_id', 'integer', ['null' => true, 'default' => 0])
              ->addForeignKey('task_id', 'tasks', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
