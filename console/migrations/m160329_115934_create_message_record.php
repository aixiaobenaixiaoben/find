<?php

use console\migrations\Common;
use yii\db\Schema;
use yii\db\Migration;

class m160329_115934_create_message_record extends Migration
{
    public function safeUp()
    {
        $this->createTable('message_record', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'number' => Schema::TYPE_STRING . ' NOT NULL',
            'event_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], Common::getTableOptions($this->db));

        $this->createIndex('message_record_number_event_id_unique', 'message_record', ['number', 'event_id'], true);

        $this->addForeignKey('message_record_user_id', 'message_record', 'user_id', 'user', 'id');
        $this->addForeignKey('message_record_event_id', 'message_record', 'event_id', 'event', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('message_record');
    }
}
