<?php

namespace app\models\master;

use Yii;
use app\models\sales\SalesOrderStandardDetail;

/**
 * This is the model class for table "master_raw_material".
 *
 * @property int $id
 * @property string $item_code
 * @property string $item_name
 * @property string $nama
 * @property int $item_category
 * @property string $uom
 * @property float|null $weight
 * @property float|null $average
 * @property string|null $type_coil
 * @property string|null $notes
 *
 * @property SalesOrderStandardDetail[] $salesOrderStandardDetails
 * @property MasterTypeProduksi $typeProduksi
 */
class MasterRawMaterial extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_raw_material';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['weight','average', 'type_coil', 'notes'], 'default', 'value' => null],
            [['item_code', 'item_category', 'uom', 'item_name', 'nama'], 'required'],
            [['item_category'], 'integer'],
            [['weight','average'], 'number'],
            [['item_code', 'uom', 'type_coil', 'notes', 'item_name', 'nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_code' => 'Item Code',
            'item_name' => 'Item Name',
            'nama' => 'Nama',
            'item_category' => 'Category',
            'uom' => 'Uom',
            'weight' => 'Weight',
            'type_coil' => 'Type Coil',
            'notes' => 'Notes',
            'average' => 'Average',
        ];
    }

    /**
     * Gets query for [[SalesOrderStandardDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalesOrderStandardDetails()
    {
        return $this->hasMany(SalesOrderStandardDetail::class, ['id_raw_material' => 'id']);
    }
}