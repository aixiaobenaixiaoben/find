<?php

use console\migrations\Common;
use yii\db\Schema;
use yii\db\Migration;

class m160211_090807_create_event extends Migration
{
    public function safeUp()
    {
        $this->createTable('event', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'theme' => Schema::TYPE_STRING . '(255) NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'is_finished' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'urgent' => 'enum("mild","urgent","emergency") NOT NULL DEFAULT "mild"',
            'occur_at' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP',
        ], Common::getTableOptions($this->db));

        $this->addForeignKey('event_user_id', 'event', 'user_id', 'user', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('event');
    }
}
