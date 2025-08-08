<?php

namespace app\models\purchasing;

use Yii;

/**
 * This is the model class for table "detail_good_reciept".
 *
 * @property int $id
 * @property int $id_header
 * @property int $id_item
 * @property float $thickness
 * @property float $width
 * @property float $qty_order
 * @property int $id_uom
 * @property float $qty_receive
 * @property float $weight_receive
 */
class DetailGoodReciept extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detail_good_reciept';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_header', 'id_item', 'thickness', 'width', 'qty_order', 'id_uom', 'qty_receive', 'weight_receive'], 'required'],
            [['id', 'id_header', 'id_item', 'id_uom'], 'integer'],
            [['thickness', 'width', 'qty_order', 'qty_receive', 'weight_receive'], 'number'],
            [['id'], 'unique'],
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
            'id_item' => 'Id Item',
            'thickness' => 'Thickness',
            'width' => 'Width',
            'qty_order' => 'Qty Order',
            'id_uom' => 'Id Uom',
            'qty_receive' => 'Qty Receive',
            'weight_receive' => 'Weight Receive',
        ];
    }
    
    public function getHeader()
    {
        return $this->hasOne(GoodReciept::class, ['id' => 'id_header']);
    }

}