<?php

namespace frontend\modules\user\controllers;

use common\models\AjaxResponse;
use common\models\User;
use frontend\modules\user\forms\ActivateAccountForm;
use frontend\modules\user\forms\ContactForm;
use frontend\modules\user\forms\LoginForm;
use frontend\modules\user\forms\ResetPasswordForm;
use frontend\modules\user\forms\sendDynamicKeyForm;
use frontend\modules\user\forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class IndexController extends \yii\web\Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['login', 'send-dynamic-key', 'activate', 'reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'sign-up', 'verify-old-email', 'change-email', 'change-password', 'contact'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                    'send-dynamic-key' => ['post'],
                    'activate' => ['get'],
                    'verify-old-email' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (Yii::$app->request->isGet) {
            $this->render('login');
        }

        if (!\Yii::$app->user->isGuest) {
            AjaxResponse::fail();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            AjaxResponse::success();
        } else {
            AjaxResponse::fail(null, $model->errors);
        }
    }

    public function actionSendDynamicKey()
    {
        $model = new SendDynamicKeyForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->sendKey()) {
            AjaxResponse::success();
        } else {
            AjaxResponse::fail(null, $model->errors);
        }

    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSignUp()
    {
        if (Yii::$app->request->isGet) {
            return $this->render('sign-up');
        }
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->signup()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $model->errors);
    }

    public function actionActivate()
    {
        $model = new ActivateAccountForm();
        if ($model->load(Yii::$app->request->get(), '') && $model->activate()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $model->errors);
    }


    public function actionVerifyOldEmail()
    {
        $new_email = Yii::$app->request->post('new_email');
        if (!$new_email) {
            AjaxResponse::fail(null, 'lack params');
        }
        if (User::findOne(['email' => $new_email])) {
            AjaxResponse::fail(null, 'the email you provided has been used');
        }
        $transaction = Yii::$app->db->beginTransaction();
        $user = User::getCurrent();
        $key = $user->generateDynamicKey();
        $user->setDynamicKey($key, User::DYNAMIC_KEY_LOGIN_LIFE);
        $user->save();

        $title = 'Dynamic Key to verify email registered in find.forfreedomandlove.com';
        $content = "<br><br>" . $key;
        $content .= "<br>" . 'the dynamic key will expired after ' . User::DYNAMIC_KEY_LOGIN_LIFE . ' minutes';
        $content .= "<br>" . 'If this dynamic key is not sent by you ,your password has disclosed.';
        $content .= 'Don\'t tell this dynamic key to anyone and you should change your password as soon as possible';
        $content .= "<br>" . 'If this dynamic key is sent by you,the new email to verify your account will be replace by' . $new_email . "<br><br><br><br><br>";
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

    public function actionChangeEmail()
    {
        if (Yii::$app->request->isGet) {
            return $this->render('change-email');
        }

        $new_email = Yii::$app->request->post('new_email');
        $key = Yii::$app->request->post('key');
        if (!$new_email || !$key) {
            AjaxResponse::fail(null, 'lack params');
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
        $content = "<br><br>" . 'Click the following link to activate the account you have registered on find.forfreedomandlove.com';
        $content .= "<br><a href=\"localhost/find/frontend/web/index.php?r=user/index/activate&user_id={$user->id}&key={$activate_key}\" target=\"_blank\">";
        $content .= "localhost/find/frontend/web/index.php?r=user/index/activate</a>";
        $content .= "<br>" . 'You can also copy the link and open it in the Address Field' . "<br><br><br>";

        Yii::$app->mailer->compose()
            ->setTo($new_email)
            ->setSubject($title)
            ->setHtmlBody($content)
            ->send();
        AjaxResponse::success();
    }

    public function actionChangePassword()
    {
        if (Yii::$app->request->isGet) {
            return $this->render('change-password');
        }

        $old_password = Yii::$app->request->post('old_password');
        $new_password = Yii::$app->request->post('new_password');
        $new_password_confirm = Yii::$app->request->post('new_password_confirm');

        if (!$old_password || !$new_password || !$new_password_confirm) {
            AjaxResponse::fail(null, 'lack params');
        }

        $user = User::getCurrent();
        if (!$user->validatePassword($old_password)) {
            AjaxResponse::fail(null, 'Your old password was incorrect. Please try again.');
        }
        if ($new_password != $new_password_confirm) {
            AjaxResponse::fail(null, 'Your confirm password is different from the new password. Please try again.');
        }
        $user->setPassword($new_password);
        $user->save();
        Yii::$app->user->logout();
        AjaxResponse::success(null, 'You have change password successfully,please login again');
    }


    public function actionContact()
    {
        if (Yii::$app->request->isGet) {
            return $this->render('contact');
        }

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->sendEmail()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $model->errors);
    }

    public function actionResetPassword()
    {
        if (Yii::$app->request->isGet) {
            return $this->render('reset-password');
        }

        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->reset()) {
            AjaxResponse::success();
        }
        AjaxResponse::fail(null, $model->errors);
    }


}
