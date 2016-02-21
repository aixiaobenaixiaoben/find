<?php
namespace frontend\modules\user\forms;

use common\models\User;
use Yii;
use yii\base\Model;


class ResetPasswordForm extends Model
{
    public $username;
    public $password;
    public $email;
    public $dynamic_key;

    private $_user;

    public function init()
    {
        parent::init();
        $this->on(Model::EVENT_AFTER_VALIDATE, function () {
            $this->requireUnblock();
            $this->requireActivated();
        });
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email', 'dynamic_key'], 'required'],
            ['username', 'string'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'message' => 'There is no user with such email.'
            ],
            ['dynamic_key', 'string', 'length' => 8],
            ['password', 'string'],
            ['dynamic_key', 'validateDynamicKey']
        ];
    }

    public function validateDynamicKey($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validateDynamicKey($this->dynamic_key))
                $this->addError($attribute, 'Incorrect username or password or dynamic key.');
        }
    }

    public function requireUnblock()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || $user->is_blocked) {
                $this->addError('username', 'This account is blocked.');
            }
        }
    }

    public function requireActivated()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->is_activated) {
                $this->addError('username', 'this account have not been activated');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function reset()
    {
        if (!$this->validate()) return false;
        $user = $this->getUser();
        $user->removeDynamicKey();
        $user->setPassword($this->password);
        $user->save();
        return true;
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
