<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "business_partner".
 *
 * @property int $id
 * @property string $nama
 * @property string $type
 * @property string $alamat
 * @property string $no_tlp
 */
class BusinessPartner extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_partner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'type', 'alamat', 'no_tlp'], 'required'],
            [['nama', 'alamat'], 'string', 'max' => 255],
            [['type', 'no_tlp'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'type' => 'Type',
            'alamat' => 'Alamat',
            'no_tlp' => 'No Tlp',
        ];
    }

}
