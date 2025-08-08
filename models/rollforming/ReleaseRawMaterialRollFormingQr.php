<?php

namespace app\models\rollforming;

use Yii;
use app\models\purchasing\GoodReciept;
use app\models\purchasing\GoodRecieptItemDetail;

/**
 * This is the model class for table "release_raw_material_roll_forming_qr".
 *
 * @property int $id
 * @property int $id_header_detail
 * @property int $id_scan_result
 *
 * @property ReleaseRawMaterialRollFormingDetail $headerDetail
 * @property GoodReciept $scanResult
 */
class ReleaseRawMaterialRollFormingQr extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'release_raw_material_roll_forming_qr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_header_detail', 'id_scan_result'], 'required'],
            [['id_header_detail', 'id_scan_result'], 'integer'],
            [['id_header_detail'], 'exist', 'skipOnError' => true, 'targetClass' => ReleaseRawMaterialRollFormingDetail::class, 'targetAttribute' => ['id_header_detail' => 'id']],
            [['id_scan_result'], 'exist', 'skipOnError' => true, 'targetClass' => GoodRecieptItemDetail::class, 'targetAttribute' => ['id_scan_result' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_header_detail' => 'Id Header Detail',
            'id_scan_result' => 'Id Scan Result',
        ];
    }

    /**
     * Gets query for [[HeaderDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHeaderDetail()
    {
        return $this->hasOne(ReleaseRawMaterialRollFormingDetail::class, ['id' => 'id_header_detail']);
    }

    /**
     * Gets query for [[ScanResult]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getScanResult()
    {
        return $this->hasOne(GoodRecieptItemDetail::class, ['id' => 'id_scan_result']);
    }
}
