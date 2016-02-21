<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/20
 * Time: 12:47
 */
namespace frontend\modules\user\forms;

use Carbon\Carbon;
use common\exceptions\HurryDynamicKeyException;
use common\models\User;
use Yii;
use yii\base\Model;

class sendDynamicKeyForm extends Model
{
    public $username;
    public $password = null;
    public $email = null;

    private $_user;

    public function init()
    {
        parent::init();
        $this->on(Model::EVENT_AFTER_VALIDATE, function () {
            if ($this->password && !$this->email) {
                $this->validatePassword();
            } elseif (!$this->password && $this->email) {
                $this->validateEmail();
            } elseif (!$this->password && !$this->email) {
                $this->addError('attributes', 'either email or password is required');
            } else {
                $this->addError('attribute', 'can not decide which scenario');
            }
        });
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            [['username', 'password'], 'string'],
            ['email', 'email'],
        ];
    }


    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }

    public function validateEmail()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || $user->email != $this->email) {
                $this->addError('email', 'Incorrect username or email.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function sendKey()
    {
        if (!$this->validate()) return false;

        $user = $this->getUser();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($user->dynamic_key && $user->validateDynamicKey($user->dynamic_key)) {
                throw new HurryDynamicKeyException('Current Dynamic key has not expired');
            }

            $key = User::generateDynamicKey();
            $user->setDynamicKey($key, User::DYNAMIC_KEY_LOGIN_LIFE);
            $user->save();


            if ($this->password) {
                $title = 'Dynamic Key to Login find.forfreedomandlove.com';
                $content = "<br><br>" . $key;
                $content .= "<br>" . 'the dynamic key will expired after ' . User::DYNAMIC_KEY_LOGIN_LIFE . ' minutes';
                $content .= "<br>" . 'If this dynamic key is not sent by you ,your password has disclosed,and you should change your password as soon as possible ' . "<br><br><br><br><br>";

            } else {
                $title = 'Dynamic Key to Reset Password for the account on find.forfreedomandlove.com';
                $content = "<br><br>" . $key;
                $content .= "<br>" . 'the dynamic key will expired after ' . User::DYNAMIC_KEY_LOGIN_LIFE . ' minutes';
                $content .= "<br><br><br><br><br><br>";

            }

            $mail = Yii::$app->mailer->compose()
                ->setTo($user->email)
                ->setSubject($title)
                ->setHtmlBody($content)
                ->send();

            if ($mail) {
                $transaction->commit();
                return true;
            }
        } catch (\yii\base\Exception $e) {
            $transaction->rollBack();
            if ($e instanceof \yii\db\Exception) {
                $this->addError('dynamic_key', 'fail to save dynamic key to database');
            } else {
                $this->addError('dynamic_key', $e->getMessage());
            }
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

}
