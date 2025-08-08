<?php

namespace app\models\rollforming;

use Yii;
use app\models\sales\SalesOrderStandard;
use app\models\rollforming\WorkingOrderRollFormingDetail;

/**
 * This is the model class for table "working_order_roll_forming".
 *
 * @property int $id
 * @property string $no_planning
 * @property int $id_so
 * @property string $so_date
 * @property string $production_date
 * @property int $type_production
 * @property string $notes
 * @property int $status
 *
 * @property SalesOrderStandard $so
 * @property WorkingOrderRollFormingDetail[] $workingOrderRollFormingDetails
 */
class WorkingOrderRollForming extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'working_order_roll_forming';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_planning', 'id_so', 'so_date', 'production_date', 'type_production'], 'required'],
            [['id_so', 'type_production', 'status'], 'integer'],
            [['so_date', 'production_date', 'notes'], 'safe'],
            [['notes'], 'string'],
            [['no_planning'], 'string', 'max' => 50],
            [['id_so'], 'exist', 'skipOnError' => true, 'targetClass' => SalesOrderStandard::class, 'targetAttribute' => ['id_so' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_planning' => 'No Planning',
            'id_so' => 'No Sales Order',
            'so_date' => 'Sales Order Date',
            'production_date' => 'Production Date',
            'type_production' => 'Type Production',
            'notes' => 'Notes',
            'status' => 'Status',
        ];
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
     * Gets query for [[WorkingOrderRollFormingDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkingOrderRollFormingDetails()
    {
        return $this->hasMany(WorkingOrderRollFormingDetail::class, ['id_header' => 'id']);
    }

    public function getProductionRollForming()
    {
        return $this->hasOne(ProductionRollForming::class, ['id_worf' => 'id']);
    }

    public function getReleaseRawMaterialRollFormingDetails()
    {
        return $this->hasMany(ReleaseRawMaterialRollFormingDetail::class, ['id_worf_detail' => 'id'])
            ->via('workingOrderRollFormingDetails');
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

    public function getStatus()
    {
        switch ($this->status) {
            case 0:
                return 'Not Released Yet';
            case 1:
                return 'Released';
            case 2:
                return 'Produced';
            case 3:
                return 'Done';
            case 4:
                return 'Partial QC Approve';
            default:
                return 'Unknown';
        }
    }

    public static function getRemainingQty($id_so_detail, $excludeHeaderId = null)
    {
        $query = \app\models\rollforming\WorkingOrderRollFormingDetail::find()
            ->where(['id_so_detail' => $id_so_detail]);

        if ($excludeHeaderId !== null) {
            $query->andWhere(['<>', 'id_header', $excludeHeaderId]);
        }

        $producedQty = $query->sum('quantity_production');
        $soDetail = \app\models\sales\SalesOrderStandardDetail::findOne($id_so_detail);

        return $soDetail ? ($soDetail->qty - $producedQty) : 0;
    }

    public static function validateQtyProductions($qtyProductions, $excludeHeaderId = null)
    {
        $errors = [];

        foreach ($qtyProductions as $id_so_detail => $qty) {
            $qty = (int)$qty;
            if ($qty <= 0) continue;

            $soDetail = \app\models\sales\SalesOrderStandardDetail::findOne($id_so_detail);
            if (!$soDetail) continue;

            $remainingQty = self::getRemainingQty($id_so_detail, $excludeHeaderId);

            if ($qty > $remainingQty) {
                $errors[] = "Qty produksi untuk item <b>{$soDetail->item->item_name}</b> melebihi sisa produksi ({$remainingQty}).";
            }
        }

        return $errors;
    }

    public function saveDetails($qtyProductions)
    {
        foreach ($qtyProductions as $id_so_detail => $qty) {
            $qty = (int)$qty;
            if ($qty <= 0) continue;

            $detail = new \app\models\rollforming\WorkingOrderRollFormingDetail();
            $detail->id_header = $this->id;
            $detail->id_so_detail = $id_so_detail;
            $detail->quantity_production = $qty;
            $detail->save();
        }
    }

    public static function getDropdownList()
    {
        return \yii\helpers\ArrayHelper::map(
            self::find()->orderBy(['production_date' => SORT_DESC])->all(),
            'id',
            function ($model) {
                return $model->no_planning;
            }
        );
    }
}
