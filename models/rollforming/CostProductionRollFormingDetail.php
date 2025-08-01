<?php

namespace app\models\rollforming;

use Yii;

/**
 * This is the model class for table "cost_production_roll_forming_detail".
 *
 * @property int $id
 * @property int $id_header
 * @property string $description
 * @property float $nominal
 * @property string $notes
 *
 * @property CostProductionRollForming $header
 */
class CostProductionRollFormingDetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cost_production_roll_forming_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_header', 'description', 'nominal', 'notes'], 'required'],
            [['id_header'], 'integer'],
            [['nominal'], 'number'],
            [['notes'], 'string'],
            [['description'], 'string', 'max' => 255],
            [['id_header'], 'exist', 'skipOnError' => true, 'targetClass' => CostProductionRollForming::class, 'targetAttribute' => ['id_header' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_header' => 'Id Header',
            'description' => 'Description',
            'nominal' => 'Nominal',
            'notes' => 'Notes',
        ];
    }

    /**
     * Gets query for [[Header]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHeader()
    {
        return $this->hasOne(CostProductionRollForming::class, ['id' => 'id_header']);
    }

}
