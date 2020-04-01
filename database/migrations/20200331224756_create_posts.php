<?php

use Phinx\Migration\AbstractMigration;

class CreatePosts extends AbstractMigration
{
   public function up()
    {
        $table = $this->table('posts');
        $table->addColumn('title', 'string')
              ->addColumn('description', 'string')
              ->addColumn('published', 'integer', ['default' => 0])
              ->addColumn('user_id', 'integer', ['default' => 0])
              ->addTimestamps()
              ->addIndex('title', ['unique' => true, 'name' => 'index_post_title'])
              ->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT', 'update' => 'NO_ACTION'])
              ->save();
    }

    public function down()
    {
        $this->dropTable('posts');
    }
}
