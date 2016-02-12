<?php

namespace frontend\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\event\Event;

/**
* EventSearch represents the model behind the search form about `common\models\event\Event`.
*/
class EventSearch extends Event
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'user_id', 'is_finished'], 'integer'],
            [['theme', 'description', 'urgent', 'occur_at', 'created_at', 'updated_at'], 'safe'],
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
$query = Event::find();

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
            'user_id' => $this->user_id,
            'is_finished' => $this->is_finished,
            'occur_at' => $this->occur_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'theme', $this->theme])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'urgent', $this->urgent]);

return $dataProvider;
}
}