<?php

use Phinx\Migration\AbstractMigration;

class CreateUserHasMetadataTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('user_has_metadata', ['id' => false]);
        $table->addColumn('user_id', 'integer')
              ->addColumn('name', 'string', ['limit' => 50])
              ->addColumn('value', 'string', ['null' => true, 'default' => ''])
              ->addColumn('changed_by', 'integer', ['default' => 0])
              ->addColumn('changed_on', 'integer', ['default' => 0])
              ->addIndex(['user_id', 'name'], ['unique' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
