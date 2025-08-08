<?php

namespace app\models\rollforming;

use Yii;
use yii\web\UploadedFile;
use app\models\sales\SalesOrderStandard;
use app\models\rollforming\WorkingOrderRollForming;

/**
 * This is the model class for table "production_roll_forming".
 *
 * @property int $id
 * @property string $no_production
 * @property int $id_so
 * @property int $id_worf
 * @property string $so_date
 * @property string $production_date
 * @property int $type_production
 * @property string|null $notes
 * @property int $status
 *
 * @property ProductionRollFormingDetail[] $productionRollFormingDetails
 * @property SalesOrderStandard $so
 * @property WorkingOrderRollForming $worf
 */
class ProductionRollForming extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'production_roll_forming';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notes'], 'default', 'value' => null],
            [['no_production', 'id_so', 'id_worf', 'so_date', 'production_date', 'type_production'], 'required'],
            [['id_so', 'id_worf', 'type_production'], 'integer'],
            [['status'], 'integer'],
            [['so_date', 'production_date'], 'safe'],
            [['notes'], 'string'],
            [['no_production'], 'string', 'max' => 50],
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
            'no_production' => 'No Production',
            'id_so' => 'Code Project',
            'id_worf' => 'No Working Order',
            'so_date' => 'Project Date',
            'production_date' => 'Production Date',
            'type_production' => 'Type Production',
            'notes' => 'Notes',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[ProductionRollFormingDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductionRollFormingDetails()
    {
        return $this->hasMany(ProductionRollFormingDetail::class, ['id_header' => 'id']);
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

    public function getNamaStatus()
    {
        switch ($this->status) {
            case 0:
                return '-';
            case 1:
                return 'Done';
            default:
                return 'Unknown';
        }
    }

    public function initializeFromWorkingOrder()
    {
        if ($this->id_worf) {
            $worf = \app\models\rollforming\WorkingOrderRollForming::findOne($this->id_worf);
            if ($worf) {
                $this->id_so = $worf->id_so;
                $this->so_date = $worf->so_date;
                $this->type_production = $worf->type_production;
                $this->notes = $worf->notes;
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

    public function saveDetailProduction($postDetails)
    {
        $postProductionDates = Yii::$app->request->post('actual_production_date', []);
        $postFinalResults = Yii::$app->request->post('final_result', []);
        $postFinalResultsWeight = Yii::$app->request->post('final_result_weight', []);
        $postWastes = Yii::$app->request->post('waste', []);
        $postPunchScraps = Yii::$app->request->post('punch_scrap', []);
        $postRefurbishes = Yii::$app->request->post('refurbish', []);
        $postRemainingCoils = Yii::$app->request->post('remaining_coil', []);

        $postFinalResultQc = Yii::$app->request->post('final_result_qc', []);
        $postRejectQc = Yii::$app->request->post('reject_qc', []);
        $postSampleResults = [];
        for ($qc = 1; $qc <= 6; $qc++) {
            for ($s = 1; $s <= 4; $s++) {
                $key = "sample_result_{$s}_qc_{$qc}";
                $postSampleResults[$key] = Yii::$app->request->post($key, []);
            }
        }

        foreach ($postDetails as $d) {
            $worfDetailId = $d['id_worf_detail'];

            $detail = new \app\models\rollforming\ProductionRollFormingDetail();
            $detail->id_header = $this->id;
            $detail->id_worf_detail = $worfDetailId;

            // Assign values from POST
            $detail->actual_production_date = $postProductionDates[$worfDetailId] ?? null;
            $detail->final_result = $postFinalResults[$worfDetailId] ?? 0;
            $detail->final_result_weight = $postFinalResultsWeight[$worfDetailId] ?? 0;
            $detail->waste = $postWastes[$worfDetailId] ?? 0;
            $detail->punch_scrap = $postPunchScraps[$worfDetailId] ?? 0;
            $detail->refurbish = $postRefurbishes[$worfDetailId] ?? 0;
            $detail->remaining_coil = $postRemainingCoils[$worfDetailId] ?? 0;

            $detail->final_result_qc = $postFinalResultQc[$worfDetailId] ?? 0;
            $detail->reject_qc = $postRejectQc[$worfDetailId] ?? 0;

            // Handle uploaded file
            $fileInputName = "document_qc[$worfDetailId]";
            $uploadedFile = UploadedFile::getInstanceByName($fileInputName);

            if ($uploadedFile) {
                $safeFilename = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $uploadedFile->name);
                $uploadPath = Yii::getAlias('@webroot/uploads/') . $safeFilename;

                if ($uploadedFile->saveAs($uploadPath)) {
                    $detail->document_qc = $safeFilename;
                }
            }
            for ($qc = 1; $qc <= 6; $qc++) {
                for ($s = 1; $s <= 4; $s++) {
                    $attr = "sample_result_{$s}_qc_{$qc}";
                    $value = $postSampleResults[$attr][$worfDetailId] ?? 0;
                    $detail->$attr = $value;
                }
            }
            $detail->save(false);
            if ($detail->reject_qc > 0) {
                $worfDetail = \app\models\rollforming\WorkingOrderRollFormingDetail::findOne($worfDetailId);
                if ($worfDetail !== null) {
                    $worfDetail->quantity_production = max(0, $worfDetail->quantity_production - $detail->reject_qc);
                    $worfDetail->save(false);
                }
            }
        }

        $totalRejectQc = \app\models\rollforming\ProductionRollFormingDetail::find()
            ->where(['id_header' => $this->id])
            ->sum('reject_qc');

        if ($this->id_worf) {
            $workingOrder = \app\models\rollforming\WorkingOrderRollForming::findOne($this->id_worf);
            if ($workingOrder) {
                if ($totalRejectQc > 0) {
                    $workingOrder->status = 4; // Jika ada reject
                } else {
                    $workingOrder->status = 2; // Default selesai tanpa reject
                }
                $workingOrder->save(false);
            }
        }
    }
}
