<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "purchase_order".
 *
 * @property int $id
 * @property string $no_po
 * @property string $tanggal
 * @property int $id_supplier
 */
class PurchaseOrder extends \yii\db\ActiveRecord
{
    /**
     * Holds the detail items for MultipleInput widget
     */
    public $detailItems = [];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_po', 'tanggal', 'id_supplier'], 'required'],
            [['tanggal'], 'safe'],
            [['id_supplier'], 'integer'],
            [['no_po'], 'string', 'max' => 255],
            [['detailItems'], 'safe'], // allow mass assignment for detailItems
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_po' => 'No Po',
            'tanggal' => 'Tanggal',
            'id_supplier' => 'Id Supplier',
            'detailItems' => 'Detail Items',
        ];
    }

    public function getSupplier(){
        return $this->hasOne(BusinessPartner::className() , ['id' => 'id_supplier']);
    }

}
