<?php

namespace frontend\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\location\LocationCurrent;

/**
* LocationCurrentSearch represents the model behind the search form about `common\models\location\LocationCurrent`.
*/
class LocationCurrentSearch extends LocationCurrent
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'user_id', 'event_id', 'is_origin'], 'integer'],
            [['title', 'occur_at', 'created_at'], 'safe'],
            [['latitude', 'longitude'], 'number'],
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
$query = LocationCurrent::find();

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
            'event_id' => $this->event_id,
            'is_origin' => $this->is_origin,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'occur_at' => $this->occur_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

return $dataProvider;
}
}