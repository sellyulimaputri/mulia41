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
 * @property float $sample_result_1_qc_1
 * @property float $sample_result_2_qc_1
 * @property float $sample_result_3_qc_1
 * @property float $sample_result_4_qc_1
 * @property float $sample_result_1_qc_2
 * @property float $sample_result_2_qc_2
 * @property float $sample_result_3_qc_2
 * @property float $sample_result_4_qc_2
 * @property float $sample_result_1_qc_3
 * @property float $sample_result_2_qc_3
 * @property float $sample_result_3_qc_3
 * @property float $sample_result_4_qc_3
 * @property float $sample_result_1_qc_4
 * @property float $sample_result_2_qc_4
 * @property float $sample_result_3_qc_4
 * @property float $sample_result_4_qc_4
 * @property float $sample_result_1_qc_5
 * @property float $sample_result_2_qc_5
 * @property float $sample_result_3_qc_5
 * @property float $sample_result_4_qc_5
 * @property float $sample_result_1_qc_6
 * @property float $sample_result_2_qc_6
 * @property float $sample_result_3_qc_6
 * @property float $sample_result_4_qc_6
 * @property float|null $final_result_qc
 * @property float|null $reject_qc
 * @property string|null $document_qc
 * 
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
            [[
                'final_result',
                'waste',
                'punch_scrap',
                'refurbish',
                'remaining_coil',
                'final_result_qc',
                'reject_qc',
                'sample_result_1_qc_1',
                'sample_result_2_qc_1',
                'sample_result_3_qc_1',
                'sample_result_4_qc_1',
                'sample_result_1_qc_2',
                'sample_result_2_qc_2',
                'sample_result_3_qc_2',
                'sample_result_4_qc_2',
                'sample_result_1_qc_3',
                'sample_result_2_qc_3',
                'sample_result_3_qc_3',
                'sample_result_4_qc_3',
                'sample_result_1_qc_4',
                'sample_result_2_qc_4',
                'sample_result_3_qc_4',
                'sample_result_4_qc_4',
                'sample_result_1_qc_5',
                'sample_result_2_qc_5',
                'sample_result_3_qc_5',
                'sample_result_4_qc_5',
                'sample_result_1_qc_6',
                'sample_result_2_qc_6',
                'sample_result_3_qc_6',
                'sample_result_4_qc_6',
            ], 'number'],
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
            'sample_result_1_qc_1' => 'Result 1 QC 1',
            'sample_result_2_qc_1' => 'Result 2 QC 1',
            'sample_result_3_qc_1' => 'Result 3 QC 1',
            'sample_result_4_qc_1' => 'Result 4 QC 1',
            'sample_result_1_qc_2' => 'Result 1 QC 2',
            'sample_result_2_qc_2' => 'Result 2 QC 2',
            'sample_result_3_qc_2' => 'Result 3 QC 2',
            'sample_result_4_qc_2' => 'Result 4 QC 2',
            'sample_result_1_qc_3' => 'Result 1 QC 3',
            'sample_result_2_qc_3' => 'Result 2 QC 3',
            'sample_result_3_qc_3' => 'Result 3 QC 3',
            'sample_result_4_qc_3' => 'Result 4 QC 3',
            'sample_result_1_qc_4' => 'Result 1 QC 4',
            'sample_result_2_qc_4' => 'Result 2 QC 4',
            'sample_result_3_qc_4' => 'Result 3 QC 4',
            'sample_result_4_qc_4' => 'Result 4 QC 4',
            'sample_result_1_qc_5' => 'Result 1 QC 5',
            'sample_result_2_qc_5' => 'Result 2 QC 5',
            'sample_result_3_qc_5' => 'Result 3 QC 5',
            'sample_result_4_qc_5' => 'Result 4 QC 5',
            'sample_result_1_qc_6' => 'Result 1 QC 6',
            'sample_result_2_qc_6' => 'Result 2 QC 6',
            'sample_result_3_qc_6' => 'Result 3 QC 6',
            'sample_result_4_qc_6' => 'Result 4 QC 6',
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
