<?php
namespace frontend\modules\user\forms;

use Carbon\Carbon;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    /** @var  String *用户名 */
    public $username;
    /** @var  String *登陆密码 */
    public $password;
    /** @var  String *动态口令,由字符数字-_组成 */
    public $dynamic_key;
    /** @var bool *是否记住登陆状态 */
    public $rememberMe = true;

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
            [['username', 'password', 'dynamic_key'], 'required'],
            ['username', 'string'],
            ['dynamic_key', 'string', 'length' => 8],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['dynamic_key', 'validateDynamicKey']
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password or dynamic key.');
            }
        }
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
    public function login()
    {
        if (!$this->validate()) return false;
        $user = $this->getUser();
        $user->removeDynamicKey();
        $user->save();
        return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
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
