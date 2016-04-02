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
    /** @var  String *接受邮件的邮箱 */
    public $email;
    /** @var  String *邮件主题 */
    public $subject;
    /** @var  String *邮件正文 */
    public $body;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'subject', 'body'], 'required'],
            ['email', 'email'],
            ['subject', 'string', 'max' => 30],
            ['body', 'string', 'max' => 500],
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
        if (!$this->validate()) return false;
        $mail = Yii::$app->mailer->compose()
            ->setTo($this->email)
            ->setSubject($this->subject)
            ->setHtmlBody($this->body)
            ->send();
        return $mail;
    }
}
