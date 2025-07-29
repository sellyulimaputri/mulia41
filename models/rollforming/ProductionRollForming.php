<?php

namespace app\models\rollforming;

use Yii;
use app\models\sales\SalesOrderStandard;
use app\models\rollforming\WorkingOrderRollForming;

/**
 * This is the model class for table "production_roll_forming".
 *
 * @property int $id
 * @property string $no_production
 * @property int $id_so
 * @property int $id_worf
 * @property string $so_date
 * @property string $production_date
 * @property int $type_production
 * @property string|null $notes
 *
 * @property ProductionRollFormingDetail[] $productionRollFormingDetails
 * @property SalesOrderStandard $so
 * @property WorkingOrderRollForming $worf
 */
class ProductionRollForming extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'production_roll_forming';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notes'], 'default', 'value' => null],
            [['no_production', 'id_so', 'id_worf', 'so_date', 'production_date', 'type_production'], 'required'],
            [['id_so', 'id_worf', 'type_production'], 'integer'],
            [['so_date', 'production_date'], 'safe'],
            [['notes'], 'string'],
            [['no_production'], 'string', 'max' => 50],
            [['id_so'], 'exist', 'skipOnError' => true, 'targetClass' => SalesOrderStandard::class, 'targetAttribute' => ['id_so' => 'id']],
            [['id_worf'], 'exist', 'skipOnError' => true, 'targetClass' => WorkingOrderRollForming::class, 'targetAttribute' => ['id_worf' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_production' => 'No Production',
            'id_so' => 'Id So',
            'id_worf' => 'Id Worf',
            'so_date' => 'So Date',
            'production_date' => 'Production Date',
            'type_production' => 'Type Production',
            'notes' => 'Notes',
        ];
    }

    /**
     * Gets query for [[ProductionRollFormingDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductionRollFormingDetails()
    {
        return $this->hasMany(ProductionRollFormingDetail::class, ['id_header' => 'id']);
    }

    /**
     * Gets query for [[So]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSo()
    {
        return $this->hasOne(SalesOrderStandard::class, ['id' => 'id_so']);
    }

    /**
     * Gets query for [[Worf]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorf()
    {
        return $this->hasOne(WorkingOrderRollForming::class, ['id' => 'id_worf']);
    }

}