<?php

use console\migrations\Common;
use yii\db\Schema;
use yii\db\Migration;

class m160211_104111_create_location_provider extends Migration
{
    public function safeUp()
    {
        $this->createTable('location_provider', [
            'id' => Schema::TYPE_PK,
            'identity_kind' => 'enum("police","monitor_system","people") NOT NULL DEFAULT "police"',
            'identity_info' => Schema::TYPE_STRING . '(255) NOT NULL',
            'latitude' => Schema::TYPE_DECIMAL . '(13,10) DEFAULT NULL',
            'longitude' => Schema::TYPE_DECIMAL . '(13,10) DEFAULT NULL',
            'provided_at' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], Common::getTableOptions($this->db));

        $this->createIndex('provider_identity_info', 'location_provider', 'identity_info', true);
    }

    public function safeDown()
    {
        $this->dropTable('location_provider');
    }
}
