<?php

namespace app\models\rollforming;

use Yii;
use app\models\sales\SalesOrderStandard;
use app\models\rollforming\ProductionRollForming;
use app\models\rollforming\WorkingOrderRollForming;
use app\models\rollforming\CostProductionRollFormingDetail;

/**
 * This is the model class for table "cost_production_roll_forming".
 *
 * @property int $id
 * @property int $id_production
 * @property int $id_worf
 * @property int $id_so
 * @property string $so_date
 * @property string $production_date
 * @property int $type_production
 * @property string|null $notes
 *
 * @property CostProductionRollFormingDetail[] $costProductionRollFormingDetails
 * @property ProductionRollForming $production
 * @property SalesOrderStandard $so
 * @property WorkingOrderRollForming $worf
 */
class CostProductionRollForming extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cost_production_roll_forming';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notes'], 'default', 'value' => null],
            [['id_production', 'id_worf', 'id_so', 'so_date', 'production_date', 'type_production'], 'required'],
            [['id_production', 'id_worf', 'id_so', 'type_production'], 'integer'],
            [['so_date', 'production_date'], 'safe'],
            [['notes'], 'string'],
            [['id_production'], 'exist', 'skipOnError' => true, 'targetClass' => ProductionRollForming::class, 'targetAttribute' => ['id_production' => 'id']],
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
            'id_production' => 'No Production',
            'id_worf' => 'No Working Order',
            'id_so' => 'No Sales Order',
            'so_date' => 'Sales Order Date',
            'production_date' => 'Production Date',
            'type_production' => 'Type Production',
            'notes' => 'Notes',
        ];
    }

    /**
     * Gets query for [[CostProductionRollFormingDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCostProductionRollFormingDetails()
    {
        return $this->hasMany(CostProductionRollFormingDetail::class, ['id_header' => 'id']);
    }

    /**
     * Gets query for [[Production]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduction()
    {
        return $this->hasOne(ProductionRollForming::class, ['id' => 'id_production']);
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

    public function getNamaTypeProduction()
    {
        switch ($this->type_production) {
            case 1:
                return 'Roll Forming';
            case 2:
                return 'Powder Coating';
            default:
                return 'Unknown';
        }
    }
}
