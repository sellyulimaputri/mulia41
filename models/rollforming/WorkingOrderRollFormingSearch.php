<?php

namespace app\models\rollforming;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\rollforming\WorkingOrderRollForming;

/**
 * WorkingOrderRollFormingSearch represents the model behind the search form of `app\models\rollforming\WorkingOrderRollForming`.
 */
class WorkingOrderRollFormingSearch extends WorkingOrderRollForming
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_release'], 'safe'],

            [['id', 'id_so', 'type_production'], 'integer'],
            [['no_planning', 'so_date', 'production_date', 'notes'], 'safe'],
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
        $query = WorkingOrderRollForming::find();

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
            'id_so' => $this->id_so,
            'so_date' => $this->so_date,
            'production_date' => $this->production_date,
            'type_production' => $this->type_production,
        ]);

        $query->andFilterWhere(['like', 'no_planning', $this->no_planning])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        if ($this->status_release === '1') {
            $query->andWhere('EXISTS (
        SELECT 1 FROM release_raw_material_roll_forming r
        WHERE r.id_worf = working_order_roll_forming.id
    )');
        } elseif ($this->status_release === '0') {
            $query->andWhere('NOT EXISTS (
        SELECT 1 FROM release_raw_material_roll_forming r
        WHERE r.id_worf = working_order_roll_forming.id
    )');
        }

        return $dataProvider;
    }
}