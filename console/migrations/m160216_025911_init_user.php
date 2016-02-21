<?php

use yii\db\Schema;
use yii\db\Migration;

class m160216_025911_init_user extends Migration
{

    public function safeUp()
    {
        $this->insert('user', [
            'id' => 1,
            'username' => 'aixiaoben',
            'email' => '357620917@qq.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('aixiaoben'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'is_activated' => true,
        ]);

        $this->insert('admin', [
            'id' => 1,
            'user_id' => 1,
        ]);
    }

    public function safeDown()
    {
        $this->delete('admin', ['id' => 1]);
        $this->delete('user', ['id' => 1]);
    }

}
