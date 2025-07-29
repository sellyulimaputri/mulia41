<?php

namespace app\models\sales;

use Yii;
use app\models\master\MasterItem;
use app\models\master\MasterRawMaterial;
use app\models\sales\SalesOrderStandard;
use app\models\master\MasterTypeProduksi;

/**
 * This is the model class for table "sales_order_standard_detail".
 *
 * @property int $id
 * @property int $id_item
 * @property int $type_produksi
 * @property int $id_raw_material
 * @property string $description
 * @property float $length
 * @property float $qty
 * @property float $harga
 * @property float $total
 * @property int $id_header
 *
 * @property SalesOrderStandard $header
 * @property MasterItem $item
 * @property MasterRawMaterial $rawMaterial
 * @property MasterTypeProduksi $typeProduksi
 */
class SalesOrderStandardDetail extends \yii\db\ActiveRecord
{
    public $remaining_qty;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_order_standard_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_item', 'length', 'qty', 'harga', 'total', 'id_header'], 'required'],
            [['id_item', 'type_produksi', 'id_raw_material', 'id_header'], 'integer'],
            [['id_raw_material'], 'default', 'value' => null],
            [['length', 'qty', 'harga', 'total'], 'number'],
            [['description'], 'string', 'max' => 255],
            [['id_header'], 'exist', 'skipOnError' => true, 'targetClass' => SalesOrderStandard::class, 'targetAttribute' => ['id_header' => 'id']],
            [['id_item'], 'exist', 'skipOnError' => true, 'targetClass' => MasterItem::class, 'targetAttribute' => ['id_item' => 'id']],
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
            'id_item' => 'Id Item',
            'type_produksi' => 'Id Type Produksi',
            'id_raw_material' => 'Id Raw Material',
            'description' => 'Description',
            'length' => 'Length',
            'qty' => 'Qty',
            'harga' => 'Harga',
            'total' => 'Total',
            'id_header' => 'Id Header',
        ];
    }

    /**
     * Gets query for [[Header]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHeader()
    {
        return $this->hasOne(SalesOrderStandard::class, ['id' => 'id_header']);
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(MasterItem::class, ['id' => 'id_item']);
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

    /**
     * Mendapatkan nama tipe produksi.
     * 
     * @return string
     */
    public function getNamaTypeProduksi()
    {
        switch ($this->type_produksi) {
            case 1:
                return 'Galvanish';
            case 2:
                return 'Powder Coating';
            default:
                return 'Unknown';
        }
    }
}
