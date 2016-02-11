<?php

namespace console\migrations;

use yii\db\Schema;

/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/11
 * Time: 11:16
 */
class Common
{

    public static function getTableOptions($db)
    {
        $table_options = "";
        if ($db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $table_options .= 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $table_options;
    }

}