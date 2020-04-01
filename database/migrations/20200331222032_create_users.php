<?php

use Phinx\Migration\AbstractMigration;

class CreateUsers extends AbstractMigration
{

    public function up()
    {
        $table = $this->table('users');
        $table->addColumn('name', 'string', ['limit' => 65])
              ->addColumn('email', 'string', ['limit' => 84])
              ->addColumn('password', 'string', ['limit' => 255])
              ->addColumn('avatar', 'string', ['limit' => 100])
              ->addColumn('confirmation_key', 'string')
              ->addColumn('confirmation_expires', 'datetime')
              ->addColumn('is_confirmation', 'integer', ['default' => 0])
              ->addTimestamps()
              ->addIndex('email', ['unique' => true, 'name' => 'index_users_email'])
              ->save();
    }

    public function down()
    {
        $this->dropTable('users');
    }

}
