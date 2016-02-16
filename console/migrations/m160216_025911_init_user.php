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
        ]);
    }

    public function safeDown()
    {
        $this->delete('user', ['id' => 1]);
    }

}
