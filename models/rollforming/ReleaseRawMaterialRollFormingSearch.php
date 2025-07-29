<?php

namespace app\models\rollforming;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\rollforming\ReleaseRawMaterialRollForming;

/**
 * ReleaseRawMaterialRollFormingSearch represents the model behind the search form of `app\models\rollforming\ReleaseRawMaterialRollForming`.
 */
class ReleaseRawMaterialRollFormingSearch extends ReleaseRawMaterialRollForming
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_so', 'id_worf', 'type_production'], 'integer'],
            [['no_release', 'so_date', 'worf_date', 'notes'], 'safe'],
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
        $query = ReleaseRawMaterialRollForming::find();

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
            'id_worf' => $this->id_worf,
            'so_date' => $this->so_date,
            'worf_date' => $this->worf_date,
            'type_production' => $this->type_production,
        ]);

        $query->andFilterWhere(['like', 'no_release', $this->no_release])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
