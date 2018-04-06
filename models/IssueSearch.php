<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Issue;

/**
 * IssueSearch represents the model behind the search form of `app\models\Issue`.
 */
class IssueSearch extends Issue
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'issuetype_id', 'issuepriority_id', 'issuestatus_id', 'sprint_id', 'version_id', 'resolved_version_id', 'detected_version_id', 'performer_id', 'owner_id', 'parentissue_id', 'relatedissue_id'], 'integer'],
            [['name', 'description', 'create_date', 'finish_date', 'deadline'], 'safe'],
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
    public function search($params, $conditions = [])
    {
        $query = Issue::find();

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
            'create_date' => $this->create_date,
            'finish_date' => $this->finish_date,
            'deadline' => $this->deadline,
            'issuetype_id' => $this->issuetype_id,
            'issuepriority_id' => $this->issuepriority_id,
            'issuestatus_id' => $this->issuestatus_id,
            'sprint_id' => $this->sprint_id,
            'version_id' => $this->version_id,
            'resolved_version_id' => $this->resolved_version_id,
            'detected_version_id' => $this->detected_version_id,
            'performer_id' => $this->performer_id,
            'owner_id' => $this->owner_id,
            'project_id' => $this->project_id,
            'parentissue_id' => $this->parentissue_id,
            'relatedissue_id' => $this->relatedissue_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        if (!empty($conditions)){
            foreach ($conditions as $condition) $query->andFilterWhere($condition);
        }

        return $dataProvider;
    }
}
