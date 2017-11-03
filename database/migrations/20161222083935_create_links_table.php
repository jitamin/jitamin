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

class CreateLinksTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('links');
        $table->addColumn('label', 'string')
              ->addColumn('opposite_id', 'integer', ['null' => true, 'default' => 0])
              ->addIndex(['label'], ['unique' => true])
              ->create();
    }
}
