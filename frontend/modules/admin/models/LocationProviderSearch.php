<?php

namespace frontend\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\location\LocationProvider;

/**
* LocationProviderSearch represents the model behind the search form about `common\models\location\LocationProvider`.
*/
class LocationProviderSearch extends LocationProvider
{
/**
* @inheritdoc
*/
public function rules()
{
return [
[['id'], 'integer'],
            [['identity_kind', 'identity_info', 'provided_at', 'created_at'], 'safe'],
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
$query = LocationProvider::find();

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
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'provided_at' => $this->provided_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'identity_kind', $this->identity_kind])
            ->andFilterWhere(['like', 'identity_info', $this->identity_info]);

return $dataProvider;
}
}