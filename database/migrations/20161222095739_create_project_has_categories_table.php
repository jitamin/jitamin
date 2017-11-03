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

class CreateProjectHasCategoriesTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_has_categories');
        $table->addColumn('name', 'string')
              ->addColumn('project_id', 'integer')
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('position', 'integer', ['null' => true, 'default' => 0])
              ->addIndex(['project_id', 'name'], ['unique' => true])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
