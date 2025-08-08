<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "type_coil".
 *
 * @property int $id
 * @property string $nama
 */
class TypeCoil extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'type_coil';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['id'], 'integer'],
            [['nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            //'id' => 'ID',
            'nama' => 'Nama',
        ];
    }

}
