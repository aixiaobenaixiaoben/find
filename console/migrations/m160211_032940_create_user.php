<?php

use console\migrations\Common;
use yii\db\Schema;
use yii\db\Migration;

class m160211_032940_create_user extends Migration
{
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . '(40) NOT NULL',
            'email' => Schema::TYPE_STRING . '(255) NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . '(255) NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING . '(255) DEFAULT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',

            'dynamic_key' => Schema::TYPE_STRING . '(10) DEFAULT NULL',
            'is_block' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'is_activated' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',

            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' NULL DEFAULT NULL',
        ], Common::getTableOptions($this->db));

        $this->createIndex('user_username_unique', 'user', 'username', true);
        $this->createIndex('user_email_unique', 'user', 'email', true);
        $this->createIndex('user_auth_key_unique', 'user', 'auth_key', true);
        $this->createIndex('user_password_reset_token', 'user', 'password_reset_token', true);
    }

    public function safeDown()
    {
        $this->dropTable('user');
    }

}
