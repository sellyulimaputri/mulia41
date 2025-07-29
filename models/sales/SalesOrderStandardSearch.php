<?php

namespace app\models\sales;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\sales\SalesOrderStandard;

/**
 * SalesOrderStandardSearch represents the model behind the search form of `app\models\sales\SalesOrderStandard`.
 */
class SalesOrderStandardSearch extends SalesOrderStandard
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_customer'], 'integer'],
            [['no_so', 'tanggal', 'deliver_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = SalesOrderStandard::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tanggal' => $this->tanggal,
            'id_customer' => $this->id_customer,
            'deliver_date' => $this->deliver_date,
        ]);

        $query->andFilterWhere(['like', 'no_so', $this->no_so]);

        return $dataProvider;
    }
}
