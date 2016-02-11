<?php

use console\migrations\Common;
use yii\db\Schema;
use yii\db\Migration;

class m160211_115947_create_location_current extends Migration
{
    public function safeUp()
    {
        $this->createTable('location_current', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'case_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'is_origin' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'latitude' => Schema::TYPE_DECIMAL . '(13,10) NOT NULL',
            'longitude' => Schema::TYPE_DECIMAL . '(13,10) NOT NULL',
            'occur_at' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], Common::getTableOptions($this->db));

        $this->addForeignKey('location_current_user_id', 'location_current', 'user_id', 'user', 'id');
        $this->addForeignKey('location_current_case_id', 'location_current', 'case_id', 'case', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('location_current');
    }
}
