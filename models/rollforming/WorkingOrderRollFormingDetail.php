<?php

namespace app\models\rollforming;

use Yii;
use app\models\sales\SalesOrderStandardDetail;
use app\models\rollforming\WorkingOrderRollForming;

/**
 * This is the model class for table "working_order_roll_forming_detail".
 *
 * @property int $id
 * @property int $id_header
 * @property int $id_so_detail
 * @property int $quantity_production
 *
 * @property WorkingOrderRollForming $header
 * @property SalesOrderStandardDetail $soDetail
 */
class WorkingOrderRollFormingDetail extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'working_order_roll_forming_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_header', 'id_so_detail', 'quantity_production'], 'required'],
            [['id_header', 'id_so_detail', 'quantity_production'], 'integer'],
            [['id_header'], 'exist', 'skipOnError' => true, 'targetClass' => WorkingOrderRollForming::class, 'targetAttribute' => ['id_header' => 'id']],
            [['id_so_detail'], 'exist', 'skipOnError' => true, 'targetClass' => SalesOrderStandardDetail::class, 'targetAttribute' => ['id_so_detail' => 'id']],
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
            'id_so_detail' => 'Id So Detail',
            'quantity_production' => 'Quantity Production',
        ];
    }

    /**
     * Gets query for [[Header]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHeader()
    {
        return $this->hasOne(WorkingOrderRollForming::class, ['id' => 'id_header']);
    }

    /**
     * Gets query for [[SoDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoDetail()
    {
        return $this->hasOne(SalesOrderStandardDetail::class, ['id' => 'id_so_detail']);
    }
}