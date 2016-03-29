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
     * the rules for access control
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
     * the action to handle the error requests
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
     * Home Page of Site
     * @return string content of home page
     */
    public function actionIndex()
    {
        $admin = Admin::getCurrent();
        return $this->render('index', ['admin' => $admin]);
    }

    /**
     * login interface
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
     * method to send dynamic key to user's email
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
     * logout interface
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     *
     * @return string content of page to display profile
     */
    public function actionProfile()
    {
        return $this->render('profile', ['name' => User::getCurrent()->username]);
    }

    /**
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
     * active account after signing up or change email which will be used to identify user
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
     * verify the old email of user
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
     * change the email to identify the account
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
     * change the password for the current account
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
     * fill a form to contact with other users
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
     * reset password for the current account
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
