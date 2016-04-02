<?php

namespace frontend\modules\user\controllers;

use common\models\admin\Admin;
use common\models\AjaxResponse;
use common\models\User;
use frontend\modules\user\forms\ActivateAccountForm;
use frontend\modules\user\forms\ContactForm;
use frontend\modules\user\forms\LoginForm;
use frontend\modules\user\forms\ResetPasswordForm;
use frontend\modules\user\forms\SendDynamicKeyForm;
use frontend\modules\user\forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Class IndexController
 * @package frontend\modules\user\controllers
 */
class IndexController extends \yii\web\Controller
{

    /**
     **用户访问权限规则,包括用户只能在登陆状态下访问的方法,和访问各个方法时只能选择的请求方法的种类(post还是get).
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'profile', 'sign-up', 'verify-old-email', 'change-email', 'change-password', 'contact'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'login' => ['get', 'post'],
                    'send-dynamic-key' => ['post'],
                    'logout' => ['get'],
                    'profile' => ['get'],
                    'sign-up' => ['get', 'post'],
                    'activate' => ['get'],
                    'verify-old-email' => ['post'],
                    'change-email' => ['get', 'post'],
                    'change-password' => ['get', 'post'],
                    'contact' => ['get', 'post'],
                    'reset-password' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     **处理系统所有错误请求或者HTTP异常的方法,将显示一个错误页面,包括错误码和错误信息摘要.
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     **显示主页.
     * @return string content of home page
     */
    public function actionIndex()
    {
        $admin = Admin::getCurrent();
        return $this->render('index', ['admin' => $admin]);
    }

    /**
     **显示登陆页面(get),登陆(post).
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            if (Yii::$app->request->isAjax) {
                AjaxResponse::fail();
            } else {
                return $this->redirect('https://find.forfreedomandlove.com');
            }
        }

        if (Yii::$app->request->isGet) {
            $csrf = Yii::$app->request->csrfToken;
            return $this->render('login', ['csrf' => $csrf]);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            AjaxResponse::success();
        } else {
            AjaxResponse::fail(null, $model->errors);
        }
    }

    /**
     **向账号的认证邮箱发送动态密码,适用场景为:登陆,忘记并重设密码,修改邮箱.
     */
    public function actionSendDynamicKey()
    {
        $model = new SendDynamicKeyForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->sendKey()) {
            AjaxResponse::success();
        } else {
            AjaxResponse::fail(null, $model->errors);
        }

    }

    /**
     **登出.
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     **进入个人中心页面.
     * @return string content of page to display profile
     */
    public function actionProfile()
    {
        return $this->render('profile', ['name' => User::getCurrent()->username]);
    }

    /**
     **显示注册页面(get);已登陆状态下注册一个新账号,会将激活新账号的链接发到新注册账号的认证邮箱(post).
     * @return string status for signing up
     */
    public function actionSignUp()
    {
        if (Yii::$app->request->isGet) {
            $csrf = Yii::$app->request->csrfToken;
            return $this->render('sign-up', ['csrf' => $csrf]);
        }
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->signup()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $model->errors);
    }

    /**
     **新注册用户或者修改认证邮箱的用户点击账号当前认证邮箱中的激活链接以激活账号.
     * @return string|\yii\web\Response
     */
    public function actionActivate()
    {
        $model = new ActivateAccountForm();
        if ($model->load(Yii::$app->request->get(), '') && $model->activate()) {
            $title = 'Congratulations! You have activated your account and you can login now';
            return $this->render('activate', ['title' => $title]);
        }
        return $this->goHome();
    }


    /**
     **在修改当前账号的认证邮箱的时候,会通过向账号的当前认证邮箱发送动态密码来验证当前邮箱,确保修改认证邮箱的操作是认证用户所为.
     * @throws \yii\db\Exception
     */
    public function actionVerifyOldEmail()
    {
        $new_email = Yii::$app->request->post('new_email');
        if (!$new_email) {
            AjaxResponse::fail(null, 'New email is required');
        }
        if (User::findOne(['email' => $new_email])) {
            AjaxResponse::fail(null, 'The new email you provided has been used');
        }
        $transaction = Yii::$app->db->beginTransaction();
        $user = User::getCurrent();
        $key = $user->generateDynamicKey();
        $user->setDynamicKey($key, User::DYNAMIC_KEY_LOGIN_LIFE);
        $user->save();

        $title = 'Dynamic Key to verify email registered in find.forfreedomandlove.com';
        $content = "<br><br>" . $key;
        $content .= "<br>" . 'the dynamic key will expired after ' . User::DYNAMIC_KEY_LOGIN_LIFE . ' minutes';
        $content .= "<br><br>" . 'If this dynamic key is not sent by you ,your password has disclosed.';
        $content .= 'Don\'t tell this dynamic key to anyone and you should change your password as soon as possible';
        $content .= "<br><br>" . 'If this dynamic key is sent by you,the email to verify your account will be replace by ' . $new_email . "<br><br><br><br><br>";
        $mail = Yii::$app->mailer->compose()
            ->setTo($user->email)
            ->setSubject($title)
            ->setHtmlBody($content)
            ->send();

        if ($mail) {
            $transaction->commit();
            AjaxResponse::success();
        } else {
            $transaction->rollBack();
            AjaxResponse::fail(null, 'Fail to verify the email you have provided,please provide another email or try again;');
        }
    }

    /**
     **显示修改邮箱的表单(get);修改用户的认证邮箱(post),若修改成功,将账号变为未激活状态,并自动下线,用户需要到新的认证邮箱中点击激活链接对账号进行激活.
     * @return string whether or not the email has been changed successfully
     */
    public function actionChangeEmail()
    {
        if (Yii::$app->request->isGet) {
            $csrf = Yii::$app->request->csrfToken;
            return $this->render('change-email', ['csrf' => $csrf]);
        }

        $new_email = Yii::$app->request->post('new_email');
        $key = Yii::$app->request->post('dynamic_key');
        if (!$new_email || !$key) {
            AjaxResponse::fail(null, 'Both new email and dynamic key are required');
        }

        if (User::findOne(['email' => $new_email])) {
            AjaxResponse::fail(null, 'the email you provided has been used');
        }
        $user = User::getCurrent();
        if (!$user->validateDynamicKey($key)) {
            AjaxResponse::fail(null, 'Incorrect Dynamic Key is provided');
        }
        $user->email = $new_email;
        $user->is_activated = false;
        $activate_key = User::generateDynamicKey();
        $user->setDynamicKey($activate_key, User::DYNAMIC_KEY_ACTIVATE_LIFE);
        $user->save();

        $title = 'Activate Account on find.forfreedomandlove.com';
        $content = "<br><br>" . 'Click the Activate Link to activate the account you have registered on find.forfreedomandlove.com';
        $content .= "<br><br>Activate Link: <a href=\"https://find.forfreedomandlove.com/user/index/activate/{$user->id}/{$activate_key}\" target=\"_blank\">";
        $content .= "https://find.forfreedomandlove.com/user/index/activate/{$user->id}/{$activate_key}</a>";
        $content .= "<br><br>" . 'You can also copy the link and open it in the Address Field' . "<br><br><br>";

        Yii::$app->mailer->compose()
            ->setTo($new_email)
            ->setSubject($title)
            ->setHtmlBody($content)
            ->send();
        Yii::$app->user->logout();
        AjaxResponse::success();
    }

    /**
     **显示修改账号登陆密码的表单(get),修改账号的登陆密码(post).
     * @return string whether or not the password has been changed successfully
     */
    public function actionChangePassword()
    {
        if (Yii::$app->request->isGet) {
            $csrf = Yii::$app->request->csrfToken;
            return $this->render('change-password', ['csrf' => $csrf]);
        }

        $old_password = Yii::$app->request->post('old_password');
        $new_password = Yii::$app->request->post('new_password');
        $new_password_confirm = Yii::$app->request->post('new_password_confirm');

        if (!$old_password || !$new_password || !$new_password_confirm) {
            AjaxResponse::fail(null, 'Three inputs are required');
        }
        if ($new_password != $new_password_confirm) {
            AjaxResponse::fail(null, 'Your confirm password is different from the new password. Please try again.');
        }
        $user = User::getCurrent();
        if (!$user->validatePassword($old_password)) {
            AjaxResponse::fail(null, 'Your old password was incorrect. Please try again.');
        }
        $user->setPassword($new_password);
        $user->save();
        Yii::$app->user->logout();
        AjaxResponse::success();
    }


    /**
     **显示向某个邮箱发送邮件的表单(get),向某个邮箱发送一封邮件(post).
     * @return string form to contact with other users
     */
    public function actionContact()
    {
        if (Yii::$app->request->isGet) {
            $csrf = Yii::$app->request->csrfToken;
            return $this->render('contact', ['csrf' => $csrf]);
        }

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->sendEmail()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $model->errors);
    }

    /**
     **显示重设密码的表单(get),重新设置登陆密码(post).
     * @return string whether or not the password has been reset successfully
     */
    public function actionResetPassword()
    {
        if (Yii::$app->request->isGet) {
            $csrf = Yii::$app->request->csrfToken;
            return $this->render('reset-password', ['csrf' => $csrf]);
        }

        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->reset()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $model->errors);
    }


}
