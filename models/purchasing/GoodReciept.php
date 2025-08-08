<?php

namespace app\models\purchasing;

use Yii;

/**
 * This is the model class for table "good_reciept".
 *
 * @property int $id
 * @property string $no_good_receipt
 * @property int $id_supplier
 * @property string $no_po
 * @property string $po_date
 * @property string $no_do
 * @property string $receive_date
 */
class GoodReciept extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'good_reciept';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_good_receipt', 'id_supplier', 'no_po', 'po_date', 'no_do', 'receive_date'], 'required'],
            [['id_supplier'], 'integer'],
            [['po_date', 'receive_date'], 'safe'],
            [['no_good_receipt', 'no_po', 'no_do'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_good_receipt' => 'No Good Receipt',
            'id_supplier' => 'Id Supplier',
            'no_po' => 'No Po',
            'po_date' => 'Po Date',
            'no_do' => 'No Do',
            'receive_date' => 'Receive Date',
        ];
    }
}
