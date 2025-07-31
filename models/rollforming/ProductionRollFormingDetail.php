<?php

namespace app\models\rollforming;

use Yii;

/**
 * This is the model class for table "production_roll_forming_detail".
 *
 * @property int $id
 * @property int $id_header
 * @property int $id_worf_detail
 * @property string $actual_production_date
 * @property float $final_result
 * @property float $waste
 * @property float $punch_scrap
 * @property float $refurbish
 * @property float $remaining_coil
 * @property float $final_result_qc
 * @property float $reject_qc
 * @property string $document_qc
 * @property float $sample_result_1_qc
 * @property float $sample_result_2_qc
 * @property float $sample_result_3_qc
 * @property float $sample_result_4_qc
 *
 * @property ProductionRollForming $header
 * @property WorkingOrderRollFormingDetail $worfDetail
 */
class ProductionRollFormingDetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'production_roll_forming_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_header', 'id_worf_detail'], 'required'],
            [['id_header', 'id_worf_detail'], 'integer'],
            [['actual_production_date', 'document_qc'], 'safe'],
            [['final_result', 'waste', 'punch_scrap', 'refurbish', 'remaining_coil', 'final_result_qc', 'reject_qc', 'sample_result_1_qc', 'sample_result_2_qc', 'sample_result_3_qc', 'sample_result_4_qc'], 'number'],
            [['id_header'], 'exist', 'skipOnError' => true, 'targetClass' => ProductionRollForming::class, 'targetAttribute' => ['id_header' => 'id']],
            [['id_worf_detail'], 'exist', 'skipOnError' => true, 'targetClass' => WorkingOrderRollFormingDetail::class, 'targetAttribute' => ['id_worf_detail' => 'id']],
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
            'id_worf_detail' => 'Id Worf Detail',
            'actual_production_date' => 'Actual Production Date',
            'final_result' => 'Final Result',
            'waste' => 'Waste',
            'punch_scrap' => 'Punch Scrap',
            'refurbish' => 'Refurbish',
            'remaining_coil' => 'Remaining Coil',
            'final_result_qc' => 'Final Result QC',
            'reject_qc' => 'Reject QC',
            'document_qc' => 'Document QC',
            'sample_result_1_qc' => 'Result 1 QC',
            'sample_result_2_qc' => 'Result 2 QC',
            'sample_result_3_qc' => 'Result 3 QC',
            'sample_result_4_qc' => 'Result 4 QC',
        ];
    }

    /**
     * Gets query for [[Header]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHeader()
    {
        return $this->hasOne(ProductionRollForming::class, ['id' => 'id_header']);
    }

    /**
     * Gets query for [[WorfDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorfDetail()
    {
        return $this->hasOne(WorkingOrderRollFormingDetail::class, ['id' => 'id_worf_detail']);
    }
}