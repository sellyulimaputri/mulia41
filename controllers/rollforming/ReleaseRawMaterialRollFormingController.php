<?php

namespace app\controllers\rollforming;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\purchasing\GoodReciept;
use app\models\rollforming\WorkingOrderRollForming;
use app\models\rollforming\ReleaseRawMaterialRollForming;
use app\models\rollforming\WorkingOrderRollFormingSearch;
use app\models\rollforming\ReleaseRawMaterialRollFormingSearch;

/**
 * ReleaseRawMaterialRollFormingController implements the CRUD actions for ReleaseRawMaterialRollForming model.
 */
class ReleaseRawMaterialRollFormingController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ReleaseRawMaterialRollForming models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new WorkingOrderRollFormingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReleaseRawMaterialRollForming model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ReleaseRawMaterialRollForming model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id_worf = null)
    {
        if ($id_worf) {
            $workingOrder = \app\models\rollforming\WorkingOrderRollForming::findOne($id_worf);
            if (!$workingOrder || $workingOrder->status != 0) {
                Yii::$app->session->setFlash('error', 'Data sudah direlease atau tidak valid, tidak bisa membuat release baru.');
                return $this->redirect(['index']);
            }
        }

        $model = new ReleaseRawMaterialRollForming(['id_worf' => $id_worf]);
        $model->initializeFromWorkingOrder();
        $details = $model->getWorfDetails();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (!$model->save()) {
                    throw new \Exception("Gagal menyimpan header release.");
                }

                if (!$model->saveDetailReleases(Yii::$app->request->post('Detail', []))) {
                    throw new \Exception("Gagal menyimpan detail release.");
                }

                $workingOrder->status = 1;
                $workingOrder->save(false);

                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal menyimpan release: ' . $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'details' => $details,
        ]);
    }


    /**
     * Updates an existing ReleaseRawMaterialRollForming model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Deletes an existing ReleaseRawMaterialRollForming model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Ambil semua detail release yang terkait
            $details = \app\models\rollforming\ReleaseRawMaterialRollFormingDetail::findAll(['id_header' => $model->id]);

            foreach ($details as $detail) {
                // Hapus semua QR yang terkait dengan detail tersebut
                \app\models\rollforming\ReleaseRawMaterialRollFormingQr::deleteAll(['id_header_detail' => $detail->id]);
            }

            // Hapus semua detail
            \app\models\rollforming\ReleaseRawMaterialRollFormingDetail::deleteAll(['id_header' => $model->id]);

            // Update status working order ke belum direlease
            $workingOrder = \app\models\rollforming\WorkingOrderRollForming::findOne($model->id_worf);
            if ($workingOrder !== null) {
                $workingOrder->status = 0;
                $workingOrder->save(false);
            }

            // Hapus header release
            $model->delete();

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ReleaseRawMaterialRollForming model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ReleaseRawMaterialRollForming the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReleaseRawMaterialRollForming::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetLocaters($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $itemDetail = \app\models\purchasing\GoodRecieptItemDetail::findOne($id);

        if (!$itemDetail) {
            return ['success' => false, 'message' => 'Data item detail tidak ditemukan'];
        }

        $grHeader = $itemDetail->goodReciept;

        return [
            'success' => true,
            'no_good_receipt' => $grHeader ? $grHeader->no_good_receipt : null,
            'details' => [
                [
                    'id' => $itemDetail->header->id,
                    'id_material' => $itemDetail->id_material,
                    'supplier_code' => $itemDetail->supplier_code,
                    'berat_awal' => $itemDetail->berat_awal,
                    'locater' => $itemDetail->locater ?: '(Kosong)',
                ]
            ],
        ];
    }
}