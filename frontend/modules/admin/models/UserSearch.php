<?php

namespace frontend\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
* UserSearch represents the model behind the search form about `common\models\User`.
*/
class UserSearch extends User
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'is_blocked', 'is_activated'], 'integer'],
            [['username', 'email', 'auth_key', 'password_hash', 'dynamic_key', 'dynamic_key_expired_at', 'created_at', 'updated_at'], 'safe'],
];
}

/**
* @inheritdoc
*/
public function scenarios()
{
// bypass scenarios() implementation in the parent class
return Model::scenarios();
}

/**
* Creates data provider instance with search query applied
*
* @param array $params
*
* @return ActiveDataProvider
*/
public function search($params)
{
$query = User::find();

$dataProvider = new ActiveDataProvider([
'query' => $query,
]);

$this->load($params);

if (!$this->validate()) {
// uncomment the following line if you do not want to any records when validation fails
// $query->where('0=1');
return $dataProvider;
}

$query->andFilterWhere([
            'id' => $this->id,
            'dynamic_key_expired_at' => $this->dynamic_key_expired_at,
            'is_blocked' => $this->is_blocked,
            'is_activated' => $this->is_activated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'dynamic_key', $this->dynamic_key]);

return $dataProvider;
}
}