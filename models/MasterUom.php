<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_uom".
 *
 * @property int $id
 * @property string $nama
 */
class MasterUom extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_uom';
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

    public function getItems()
    {
        return $this->hasMany(MasterItem::class, ['uom' => 'id']);
    }

    public function getRawMaterials()
    {
        return $this->hasMany(MasterRawMaterial::class, ['uom' => 'id']);
    }

}
