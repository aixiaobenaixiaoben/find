<?php
namespace common\models;

use Carbon\Carbon;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use \common\models\user\base\User as baseUser;


class User extends baseUser implements IdentityInterface
{

    const DYNAMIC_KEY_LENGTH = 8;
    const DYNAMIC_KEY_LOGIN_LIFE = 10;//minutes
    const DYNAMIC_KEY_ACTIVATE_LIFE = 24 * 60;//minutes

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function () {
                    return Carbon::now()->format('Y-m-d H:i:s');
                }
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'id_blocked' => false]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'id_blocked' => false]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @param int $length
     * @return string
     */
    public static function generateDynamicKey($length = self::DYNAMIC_KEY_LENGTH)
    {
        return strtolower(Yii::$app->security->generateRandomString($length));
    }

    /**
     * @param $key
     * @return bool
     */
    public function validateDynamicKey($key)
    {
        return $this->dynamic_key && $this->dynamic_key == $key && Carbon::now()->lte(Carbon::createFromFormat('Y-m-d H:i:s', $this->dynamic_key_expired_at));
    }

    /**
     * @param $key
     * @param $life
     */
    public function setDynamicKey($key, $life)
    {
        $this->dynamic_key = $key;
        $this->dynamic_key_expired_at = Carbon::now()->addMinutes($life);
    }

    /**
     *
     */
    public function removeDynamicKey()
    {
        $this->dynamic_key = null;
        $this->dynamic_key_expired_at = null;
    }

    /**
     * @return null|User
     */
    public static function getCurrent()
    {
        return User::findOne(Yii::$app->user->id);
    }
}
