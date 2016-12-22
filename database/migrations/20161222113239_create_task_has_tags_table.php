<?php

use Phinx\Migration\AbstractMigration;

class CreateTaskHasTagsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('project_has_tags', ['id' => false]);
        $table->addColumn('task_id', 'integer')
              ->addColumn('tag_id', 'integer')
              ->addIndex(['task_id', 'tag_id'], ['unique' => true])
              ->addForeignKey('task_id', 'tasks', 'id', ['delete' => 'CASCADE'])
              ->addForeignKey('tag_id', 'tags', 'id', ['delete' => 'CASCADE'])
              ->create();
    }
}
