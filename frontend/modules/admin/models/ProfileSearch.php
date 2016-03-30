<?php

namespace frontend\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\profile\Profile;

/**
* ProfileSearch represents the model behind the search form about `common\models\profile\Profile`.
*/
class ProfileSearch extends Profile
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'event_id', 'age', 'height'], 'integer'],
            [['name', 'gender', 'dress', 'appearance', 'created_at', 'updated_at'], 'safe'],
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
$query = Profile::find();

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
            'event_id' => $this->event_id,
            'age' => $this->age,
            'height' => $this->height,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'dress', $this->dress])
            ->andFilterWhere(['like', 'appearance', $this->appearance]);

return $dataProvider;
}
}