<?php

namespace frontend\modules\user\forms;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $email;
    public $subject;
    public $body;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'subject', 'body'], 'required'],
            ['email', 'email'],
            [['subject', 'body'], 'safe']
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @return boolean whether the email was sent
     */
    public function sendEmail()
    {
        return Yii::$app->mailer->compose()
            ->setTo($this->email)
            ->setFrom(User::getCurrent()->email)
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
