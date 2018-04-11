<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sprint;

/**
 * SprintSearch represents the model behind the search form of `app\models\Sprint`.
 */
class SprintSearch extends Sprint
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'version_id', 'project_id'], 'integer'],
            [['name', 'start_date', 'finish_date'], 'safe'],
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
        $query = Sprint::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'version_id' => $this->version_id,
            'project_id' => $this->project_id,
            'start_date' => $this->start_date,
            'finish_date' => $this->finish_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
