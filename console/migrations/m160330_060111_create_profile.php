<?php

use console\migrations\Common;
use yii\db\Schema;
use yii\db\Migration;

class m160330_060111_create_profile extends Migration
{
    public function safeUp()
    {
        $this->createTable('profile', [
            'id' => Schema::TYPE_PK,
            'event_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . '(40) NOT NULL',
            'age' => Schema::TYPE_INTEGER . ' NOT NULL',
            'gender' => 'enum("male","female") NOT NULL DEFAULT "male"',
            'height' => Schema::TYPE_INTEGER . ' NOT NULL',
            'dress' => Schema::TYPE_STRING . '(255) NOT NULL',
            'appearance' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
        ], Common::getTableOptions($this->db));

        $this->addForeignKey('profile_event_id', 'profile', 'event_id', 'event', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('profile');
    }
}
