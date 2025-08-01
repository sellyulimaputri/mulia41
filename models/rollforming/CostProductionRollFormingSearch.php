<?php

namespace app\models\rollforming;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\rollforming\CostProductionRollForming;

/**
 * CostProductionRollFormingSearch represents the model behind the search form of `app\models\rollforming\CostProductionRollForming`.
 */
class CostProductionRollFormingSearch extends CostProductionRollForming
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_production', 'id_worf', 'id_so', 'type_production'], 'integer'],
            [['so_date', 'production_date', 'notes'], 'safe'],
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
        $query = CostProductionRollForming::find();

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
            'id_production' => $this->id_production,
            'id_worf' => $this->id_worf,
            'id_so' => $this->id_so,
            'so_date' => $this->so_date,
            'production_date' => $this->production_date,
            'type_production' => $this->type_production,
        ]);

        $query->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
