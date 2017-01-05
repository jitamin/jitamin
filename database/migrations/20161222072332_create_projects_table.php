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

class CreateProjectsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('projects');
        $table->addColumn('name', 'string')
              ->addColumn('is_active', 'boolean', ['null' => true, 'default' => true])
              ->addColumn('token', 'string', ['null'=> true])
              ->addColumn('last_modified', 'biginteger', ['null' => true])
              ->addColumn('is_public', 'boolean', ['null' => true, 'default' => false])
              ->addColumn('is_private', 'boolean', ['null' => true, 'default' => false])
              ->addColumn('is_everybody_allowed', 'boolean', ['null' => true, 'default' => false])
              ->addColumn('default_swimlane', 'string', ['null' => true, 'limit' => 200, 'default' => 'Default swimlane'])
              ->addColumn('show_default_swimlane', 'integer', ['null' => true, 'default' => 1])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('identifier', 'string', ['null' => true, 'limit' => 50, 'default' => ''])
              ->addColumn('start_date', 'string', ['null' => true, 'limit' => 10, 'default' => ''])
              ->addColumn('end_date', 'string', ['null' => true, 'limit' => 10, 'default' => ''])
              ->addColumn('owner_id', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('priority_default', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('priority_start', 'integer', ['null' => true, 'default' => 0])
              ->addColumn('priority_end', 'integer', ['null' => true, 'default' => 3])
              ->create();
    }
}
