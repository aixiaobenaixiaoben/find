<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\user\base;

use Yii;

/**
 * This is the base-model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string $dynamic_key
 * @property string $dynamic_key_expired_at
 * @property boolean $is_blocked
 * @property boolean $is_activated
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \common\models\admin\Admin $admin
 * @property \common\models\event\Event[] $events
 * @property \common\models\location\LocationCurrent[] $locationCurrents
 * @property \common\models\location\LocationNew[] $locationNews
 */
abstract class User extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'auth_key', 'password_hash'], 'required'],
            [['is_blocked', 'is_activated'], 'boolean'],
            [['created_at', 'updated_at', 'dynamic_key_expired_at'], 'safe'],
            [['username'], 'string', 'max' => 40],
            [['email', 'password_hash'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['dynamic_key'], 'string', 'max' => 10],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['auth_key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'dynamic_key' => 'Dynamic Key',
            'dynamic_key_expired_at' => 'Dynamic Key Expired At',
            'is_blocked' => 'Is Blocked',
            'is_activated' => 'Is Activated',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(\common\models\admin\Admin::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(\common\models\event\Event::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationCurrents()
    {
        return $this->hasMany(\common\models\location\LocationCurrent::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationNews()
    {
        return $this->hasMany(\common\models\location\LocationNew::className(), ['user_id' => 'id']);
    }


}
