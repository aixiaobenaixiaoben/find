<?php

namespace frontend\modules\find\controllers;

use common\models\AjaxResponse;
use Yii;

class IndexController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSendMessage()
    {

    }


}
