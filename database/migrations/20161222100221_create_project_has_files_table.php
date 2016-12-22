<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Phinx\Migration\AbstractMigration;

class CreateProjectHasFilesTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_has_files');
        $table->addColumn('project_id', 'integer')
              ->addColumn('name', 'string')
              ->addColumn('path', 'string')
              ->addColumn('is_image', 'boolean', ['null' => true, 'default' => false])
              ->addColumn('size', 'integer', ['default' => 0])
              ->addColumn('user_id', 'integer', ['default' => 0])
              ->addColumn('date', 'integer', ['default' => 0])
              ->addForeignKey('project_id', 'projects', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
