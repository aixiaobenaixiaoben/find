<?php

use console\migrations\Common;
use yii\db\Schema;
use yii\db\Migration;

class m160211_105227_create_location_new extends Migration
{
    public function safeUp()
    {
        $this->createTable('location_new', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'event_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'provider_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title_from_provider' => Schema::TYPE_STRING . '(255) NOT NULL',
            'title_from_API' => Schema::TYPE_STRING . '(255) NOT NULL',
            'latitude' => Schema::TYPE_DECIMAL . '(13,10) NOT NULL',
            'longitude' => Schema::TYPE_DECIMAL . '(13,10) NOT NULL',
            'is_reliable' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 1',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], Common::getTableOptions($this->db));

        $this->createIndex('location_new_event_provider_latitude_longitude', 'location_new', ['event_id', 'provider_id', 'latitude', 'longitude'], true);

        $this->addForeignKey('location_new_user_id', 'location_new', 'user_id', 'user', 'id');
        $this->addForeignKey('location_new_event_id', 'location_new', 'event_id', 'event', 'id');
        $this->addForeignKey('location_new_provider_id', 'location_new', 'provider_id', 'location_provider', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('location_new');
    }
}
