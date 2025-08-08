<?php

namespace app\models\purchasing;

use Yii;

/**
 * This is the model class for table "good_reciept_item_detail".
 *
 * @property int $id
 * @property int $id_header
 * @property string $id_material
 * @property string $supplier_code
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
            [['id', 'id_header', 'id_material', 'supplier_code', 'berat_awal', 'locater'], 'required'],
            [['id', 'id_header'], 'integer'],
            [['berat_awal'], 'number'],
            [['id_material', 'supplier_code', 'locater'], 'string', 'max' => 255],
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
            'id_material' => 'Id Material',
            'supplier_code' => 'Supplier Code',
            'berat_awal' => 'Berat Awal',
            'locater' => 'Locater',
        ];
    }
    public function getHeader()
    {
        return $this->hasOne(DetailGoodReciept::class, ['id' => 'id_header']);
    }

    public function getGoodReciept()
    {
        return $this->hasOne(GoodReciept::class, ['id' => 'id_header'])
            ->via('header');
    }
}
