<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GoodReciept;

/**
 * GoodRecieptSearch represents the model behind the search form of `app\models\GoodReciept`.
 */
class GoodRecieptSearch extends GoodReciept
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_supplier'], 'integer'],
            [['no_good_receipt', 'no_po', 'po_date', 'no_do', 'receive_date'], 'safe'],
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
        $query = GoodReciept::find();

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
            'id_supplier' => $this->id_supplier,
            'po_date' => $this->po_date,
            'receive_date' => $this->receive_date,
        ]);

        $query->andFilterWhere(['like', 'no_good_receipt', $this->no_good_receipt])
            ->andFilterWhere(['like', 'no_po', $this->no_po])
            ->andFilterWhere(['like', 'no_do', $this->no_do]);

        return $dataProvider;
    }
}
