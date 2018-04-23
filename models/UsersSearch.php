<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UsersSearch represents the model behind the search form of `app\models\Users`.
 */
class UsersSearch extends Users
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'usertype_id', 'status', 'telegram_notify', 'mail_notify'], 'integer'],
            [['username', 'password', 'password_hash', 'password_reset_token', 'email', 'auth_key', 'telegram_key', 'first_name', 'last_name'], 'safe'],
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
        $query = Users::find();

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

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'telegram_key', $this->telegram_key])
            ->orFilterWhere(['like', 'first_name', $this->first_name])
            ->orFilterWhere(['like', 'last_name', $this->last_name]);


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'usertype_id' => $this->usertype_id,
            'status' => $this->status,
            'telegram_notify' => $this->telegram_notify,
            'mail_notify' => $this->mail_notify,
        ]);

        if (!empty($conditions)){
            foreach ($conditions as $condition) $query->andFilterWhere($condition);
        }

        return $dataProvider;
    }
}
