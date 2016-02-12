<?php

use console\migrations\Common;
use yii\db\Schema;
use yii\db\Migration;

class m160211_074424_create_admin extends Migration
{
    public function safeUp()
    {
        $this->createTable('admin', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'is_blocked' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
        ], Common::getTableOptions($this->db));

        $this->createIndex('admin_user_id_unique', 'admin', 'user_id', true);

        $this->addForeignKey('admin_user_id', 'admin', 'user_id', 'user', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('admin');
    }


}
