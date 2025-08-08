<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_item".
 *
 * @property int $id
 * @property string $item_name
 * @property string $item_code
 * @property int $id_raw_material
 * @property string|null $notes
 */
class MasterItem extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notes'], 'default', 'value' => null],
            [['item_name', 'item_code', 'id_raw_material'], 'required'],
            [['id_raw_material'], 'integer'],
            [['item_name', 'item_code', 'notes'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_name' => 'Item Name',
            'item_code' => 'Item Code',
            'id_raw_material' => 'Id Raw Material',
            'notes' => 'Notes',
        ];
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
    
}