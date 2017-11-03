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

class CreateSettingsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('settings', ['id' => false, 'primary_key' => ['option']]);
        $table->addColumn('option', 'string', ['limit' => 100])
              ->addColumn('value', 'string', ['null' => true, 'default' => ''])
              ->addColumn('changed_by', 'integer', ['default' => 0])
              ->addColumn('changed_on', 'integer', ['default' => 0])
              ->create();
    }
}
