<?php

namespace app\models\rollforming;

use Yii;
use app\models\master\MasterRawMaterial;

/**
 * This is the model class for table "release_raw_material_roll_forming_detail".
 *
 * @property int $id
 * @property int $id_header
 * @property int $id_worf_detail
 * @property int $reference_max_release
 * @property int $id_raw_material
 *
 * @property ReleaseRawMaterialRollForming $header
 * @property WorkingOrderRollFormingDetail $worfDetail
 */
class ReleaseRawMaterialRollFormingDetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'release_raw_material_roll_forming_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_header', 'id_worf_detail', 'id_raw_material', 'reference_max_release'], 'required'],
            [['id_header', 'id_worf_detail', 'id_raw_material', 'reference_max_release'], 'integer'],
            [['id_header'], 'exist', 'skipOnError' => true, 'targetClass' => ReleaseRawMaterialRollForming::class, 'targetAttribute' => ['id_header' => 'id']],
            [['id_worf_detail'], 'exist', 'skipOnError' => true, 'targetClass' => WorkingOrderRollFormingDetail::class, 'targetAttribute' => ['id_worf_detail' => 'id']],
            [['id_raw_material'], 'exist', 'skipOnError' => true, 'targetClass' => MasterRawMaterial::class, 'targetAttribute' => ['id_raw_material' => 'id']],
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
            'reference_max_release' => 'Reference Max Release',
            'id_raw_material' => 'Raw Material',
        ];
    }

    /**
     * Gets query for [[Header]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHeader()
    {
        return $this->hasOne(ReleaseRawMaterialRollForming::class, ['id' => 'id_header']);
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

    /**
     * Gets query for [[RawMaterial]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRawMaterial()
    {
        return $this->hasOne(MasterRawMaterial::class, ['id' => 'id_raw_material']);
    }
    
    
    public function getQrs()
    {
        return $this->hasMany(ReleaseRawMaterialRollFormingQr::class, ['id_header_detail' => 'id']);
    }
}