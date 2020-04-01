<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersPermissions extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users_permissions');
        $table->addColumn('is_admin', 'integer',  ['default' => 0])
              ->addColumn('user_id', 'integer',  ['default' => 0])
              ->addTimestamps()
              ->addForeignKey('user_id', 'user', 'id', ['delete' => 'RESTRICT', 'update' => 'NO_ACTION'])
              ->save();
    }

    public function down()
    {
        $this->dropTable('users_permissions');
    }
}
