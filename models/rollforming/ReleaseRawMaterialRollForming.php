<?php

namespace app\models\rollforming;

use Yii;
use app\models\sales\SalesOrderStandard;
use app\models\rollforming\WorkingOrderRollForming;
use app\models\rollforming\ReleaseRawMaterialRollFormingDetail;

/**
 * This is the model class for table "release_raw_material_roll_forming".
 *
 * @property int $id
 * @property string $no_release
 * @property int $id_so
 * @property int $id_worf
 * @property string $so_date
 * @property string $worf_date
 * @property int $type_production
 * @property string $notes
 *
 * @property ReleaseRawMaterialRollFormingDetail[] $releaseRawMaterialRollFormingDetails
 * @property SalesOrderStandard $so
 * @property WorkingOrderRollForming $worf
 */
class ReleaseRawMaterialRollForming extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'release_raw_material_roll_forming';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_release', 'id_so', 'id_worf', 'so_date', 'worf_date', 'type_production'], 'required'],
            [['id_so', 'id_worf', 'type_production'], 'integer'],
            [['so_date', 'worf_date'], 'safe'],
            [['notes'], 'string'],
            [['no_release'], 'string', 'max' => 50],
            [['id_so'], 'exist', 'skipOnError' => true, 'targetClass' => SalesOrderStandard::class, 'targetAttribute' => ['id_so' => 'id']],
            [['id_worf'], 'exist', 'skipOnError' => true, 'targetClass' => WorkingOrderRollForming::class, 'targetAttribute' => ['id_worf' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_release' => 'No Release',
            'id_so' => 'No SO',
            'id_worf' => 'No SPK',
            'so_date' => 'So Date',
            'worf_date' => 'Production Date',
            'type_production' => 'Type Production',
            'notes' => 'Notes',
        ];
    }

    /**
     * Gets query for [[ReleaseRawMaterialRollFormingDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReleaseRawMaterialRollFormingDetails()
    {
        return $this->hasMany(ReleaseRawMaterialRollFormingDetail::class, ['id_header' => 'id']);
    }

    /**
     * Gets query for [[So]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSo()
    {
        return $this->hasOne(SalesOrderStandard::class, ['id' => 'id_so']);
    }

    /**
     * Gets query for [[Worf]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorf()
    {
        return $this->hasOne(WorkingOrderRollForming::class, ['id' => 'id_worf']);
    }

    public function getNamaTypeProduction()
    {
        switch ($this->type_production) {
            case 1:
                return 'Roll Forming';
            case 2:
                return 'Powder Coating';
            default:
                return 'Unknown';
        }
    }

    public static function isAlreadyReleased($id_worf)
    {
        return self::find()->where(['id_worf' => $id_worf])->exists();
    }

    public function initializeFromWorkingOrder()
    {
        if ($this->id_worf) {
            $worf = \app\models\rollforming\WorkingOrderRollForming::findOne($this->id_worf);
            if ($worf) {
                $this->id_so = $worf->id_so;
                $this->so_date = $worf->so_date;
                $this->worf_date = $worf->production_date;
                $this->type_production = $worf->type_production;
            }
        }
    }

    public function getWorfDetails()
    {
        if ($this->id_worf) {
            return \app\models\rollforming\WorkingOrderRollFormingDetail::find()
                ->where(['id_header' => $this->id_worf])
                ->all();
        }
        return [];
    }

    public function saveDetailReleases($postDetails)
    {
        foreach ($postDetails as $d) {
            $detail = new \app\models\rollforming\ReleaseRawMaterialRollFormingDetail();
            $detail->id_header = $this->id;
            $detail->id_worf_detail = $d['id_worf_detail'];

            $detailWorf = \app\models\rollforming\WorkingOrderRollFormingDetail::findOne($d['id_worf_detail']);
            if ($detailWorf && $detailWorf->soDetail && $detailWorf->soDetail->item) {
                $length = $detailWorf->soDetail->length ?? 0;
                $quantity = $detailWorf->quantity_production ?? 0;
                $rata = $detailWorf->soDetail->item->rawMaterial->average ?? 1;

                $detail->reference_max_release = ceil(($length * $quantity) / $rata);

                $detail->id_raw_material = $detailWorf->soDetail->item->id_raw_material ?? null;
            }

            $detail->save(false);
        }
    }
}
