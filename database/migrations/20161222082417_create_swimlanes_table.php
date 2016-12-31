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

class CreateSwimlanesTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('swimlanes');
        $table->addColumn('name', 'string', ['limit' => 200])
              ->addColumn('position', 'integer', ['null' => true, 'default' => 1])
              ->addColumn('is_active', 'integer', ['null' => true, 'default' => 1])
              ->addColumn('project_id', 'integer', ['null' => true])
              ->addColumn('description', 'text', ['null' => true])
              ->addIndex(['name', 'project_id'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
