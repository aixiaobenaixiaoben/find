<?php
namespace frontend\modules\user\forms;

use Carbon\Carbon;
use common\models\admin\Admin;
use common\models\User;
use yii\base\Model;
use Yii;
use yii\db\Exception;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function init()
    {
        parent::init();
        $this->on(Model::EVENT_BEFORE_VALIDATE, function () {
            $this->requireAdmin();
        });
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function requireAdmin()
    {
        if (!$this->hasErrors()) {
            $admin = Admin::getCurrent();
            if (!$admin || $admin->is_blocked) {
                $this->addError('username', 'This account is not an admin or has been blocked as admin.');
            }
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) return false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $key = User::generateDynamicKey();

            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->setDynamicKey($key, User::DYNAMIC_KEY_ACTIVATE_LIFE);
            $user->save();

            $title = 'Activate Account on find.forfreedomandlove.com';

            $content = "<br><br>" . 'Click the following link to activate the account you have registered on find.forfreedomandlove.com';
            $content .= "<br><a href=\"localhost/find/frontend/web/index.php?r=user/index/activate&user_id={$user->id}&key={$key}\" target=\"_blank\">";
            $content .= "localhost/find/frontend/web/index.php?r=user/index/activate</a>";
            $content .= "<br>" . 'You can also copy the link and open it in the Address Field' . "<br><br><br>";

            $mail = Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setSubject($title)
                ->setHtmlBody($content)
                ->send();

            if ($mail) {
                $transaction->commit();
                return true;
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            $this->addError('user', 'fail to create account');
        }

        return false;
    }

}
