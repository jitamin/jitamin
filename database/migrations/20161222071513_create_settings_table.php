<?php

use Phinx\Migration\AbstractMigration;

class CreateSettingsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('settings', ['id' => false, 'primary_key' => ['option']]);
        $table->addColumn('option','string',['limit'=>100])
              ->addColumn('value','string',['null' => true, 'default' => ''])
              ->addColumn('changed_by','integer', ['default' => 0])
              ->addColumn('changed_on','integer', ['default' => 0])
              ->create();
    }
}
