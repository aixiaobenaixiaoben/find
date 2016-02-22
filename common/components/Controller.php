<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/22
 * Time: 22:35
 */
namespace common\components;

use Yii;

class Controller extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if(YII_DEBUG){
            $this->enableCsrfValidation=false;
        }

        /*if(!Yii::$app->request->isSecureConnection){
            $url='https://'
                .Yii::$app->getRequest()->serverName
                .Yii::$app->getRequest()->url;
            Yii::$app->request->redirect
        }*/

        return parent::beforeAction($action);
    }
}