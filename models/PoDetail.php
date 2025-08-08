<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_detail".
 *
 * @property int $id
 * @property int $id_item
 * @property float $thickness
 * @property float $width
 * @property float $qty
 * @property int $id_uom
 * @property float $harga
 * @property float $outstanding
 */
class PoDetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_item', 'qty', 'id_uom', 'harga', 'outstanding' , 'id_header'], 'required'],
            [['id_item', 'id_uom' , 'id_header' , 'id_type_coil'], 'integer'],
            [['thickness', 'width', 'qty', 'harga', 'outstanding'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_item' => 'Id Item',
            'id_type_coil' => 'Type Coil',
            'thickness' => 'Thickness',
            'width' => 'Width',
            'qty' => 'Qty',
            'id_uom' => 'Id Uom',
            'harga' => 'Harga',
            'outstanding' => 'Outstanding',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterRawMaterial::className(), ['id' => 'id_item']);
    }

    public function getTypeCoil()
    {
        return $this->hasOne(TypeCoil::className(), ['id' => 'id_type_coil']);
    }

    public function getUom()
    {
        return $this->hasOne(MasterUom::className(), ['id' => 'id_uom']);
    }

    public function getHeader()
    {
        return $this->hasOne(PurchaseOrder::className(), ['id' => 'id_header']);
    }
}
