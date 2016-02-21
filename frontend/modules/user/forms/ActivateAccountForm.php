<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 16/2/20
 * Time: 23:45
 */
namespace frontend\modules\user\forms;

use Carbon\Carbon;
use common\models\User;
use yii\base\Model;

class ActivateAccountForm extends Model
{
    public $user_id;
    public $key;

    private $_user;

    public function rules()
    {
        return [
            [['user_id', 'key'], 'required'],
            ['user_id', 'integer'],
            ['key', 'string', 'length' => 8],
            ['key', 'validateActivateKey'],
        ];
    }

    public function validateActivateKey()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || $user->is_activated || !$user->validateDynamicKey($this->key))
                $this->addError('dynamic_key', 'Fail to activate the account.');
        }
    }

    public function activate()
    {
        if (!$this->validate()) return false;
        $user = $this->getUser();
        $user->is_activated = true;
        $user->removeDynamicKey();
        $user->save();
        return true;
    }

    /**
     * @return null|User
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($this->user_id);
        }

        return $this->_user;
    }

}