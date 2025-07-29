<?php

namespace app\models\master;

use Yii;
use app\models\sales\SalesOrderStandardDetail;

/**
 * This is the model class for table "master_type_produksi".
 *
 * @property int $id
 * @property string $nama
 *
 * @property MasterRawMaterial[] $masterRawMaterials
 * @property SalesOrderStandardDetail[] $salesOrderStandardDetails
 */
class MasterTypeProduksi extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_type_produksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
        ];
    }

    /**
     * Gets query for [[MasterRawMaterials]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMasterRawMaterials()
    {
        return $this->hasMany(MasterRawMaterial::class, ['id_type_produksi' => 'id']);
    }

    /**
     * Gets query for [[SalesOrderStandardDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalesOrderStandardDetails()
    {
        return $this->hasMany(SalesOrderStandardDetail::class, ['id_type_produksi' => 'id']);
    }

}