<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_item_detail".
 *
 * @property int $id
 * @property string $id_material
 * @property float $supplier_code
 * @property float $berat_awal
 * @property string $locater
 */
class GoodRecieptItemDetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'good_reciept_item_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_material', 'id_header' , 'supplier_code', 'berat_awal', 'locater'], 'required'],
            [['berat_awal'], 'number'],
            [['supplier_code','id_material', 'locater'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_material' => 'Id Material',
            'supplier_code' => 'Supplier Code',
            'berat_awal' => 'Berat Awal',
            'locater' => 'Locater',
        ];
    }

}
