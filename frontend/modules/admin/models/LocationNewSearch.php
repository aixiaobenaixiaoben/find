<?php

namespace frontend\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\location\LocationNew;

/**
* LocationNewSearch represents the model behind the search form about `common\models\location\LocationNew`.
*/
class LocationNewSearch extends LocationNew
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id', 'user_id', 'event_id', 'provider_id', 'is_reliable'], 'integer'],
            [['title_from_provider', 'title_from_API', 'created_at'], 'safe'],
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
$query = LocationNew::find();

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
            'provider_id' => $this->provider_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_reliable' => $this->is_reliable,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'title_from_provider', $this->title_from_provider])
            ->andFilterWhere(['like', 'title_from_API', $this->title_from_API]);

return $dataProvider;
}
}