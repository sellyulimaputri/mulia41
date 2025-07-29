<?php

namespace app\models\master;

use Yii;

/**
 * This is the model class for table "master_customer".
 *
 * @property int $id
 * @property string $nama
 */
class MasterCustomer extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
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
        ];
    }

    public static function getDropdownList()
    {
        return \yii\helpers\ArrayHelper::map(
            self::find()->orderBy('nama')->all(),
            'id',
            'nama'
        );
    }
}
