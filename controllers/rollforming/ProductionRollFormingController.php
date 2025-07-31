<?php

namespace app\controllers\rollforming;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\rollforming\ProductionRollForming;
use app\models\rollforming\WorkingOrderRollForming;
use app\models\rollforming\ProductionRollFormingDetail;
use app\models\rollforming\ProductionRollFormingSearch;
use app\models\rollforming\WorkingOrderRollFormingSearch;

/**
 * ProductionRollFormingController implements the CRUD actions for ProductionRollForming model.
 */
class ProductionRollFormingController extends Controller
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
     * Lists all ProductionRollForming models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new WorkingOrderRollFormingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, null, 1);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductionRollForming model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        foreach ($model->productionRollFormingDetails as $detail) {
            if ($detail->worfDetail && $detail->worfDetail->soDetail) {
                $soDetail = $detail->worfDetail->soDetail;
                $soDetail->remaining_qty = \app\models\rollforming\WorkingOrderRollForming::getRemainingQty($soDetail->id);
            }
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }


    /**
     * Creates a new ProductionRollForming model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id_worf = null)
    {
        if ($id_worf) {
            $workingOrder = \app\models\rollforming\WorkingOrderRollForming::findOne($id_worf);

            if (!$workingOrder || $workingOrder->status != 1) {
                Yii::$app->session->setFlash('error', 'Data sudah direlease atau tidak valid, tidak bisa membuat release baru.');
                return $this->redirect(['index']);
            }
        }

        $model = new ProductionRollForming(['id_worf' => $id_worf]);
        $model->initializeFromWorkingOrder();

        $details = $model->getWorfDetails();
        foreach ($details as $detail) {
            if ($detail->soDetail) {
                $detail->soDetail->remaining_qty = WorkingOrderRollForming::getRemainingQty($detail->soDetail->id);
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveDetailProduction(Yii::$app->request->post('Detail', []));

            $workingOrder = \app\models\rollforming\WorkingOrderRollForming::findOne($id_worf);
            if ($workingOrder !== null) {
                $workingOrder->status = 2;
                $workingOrder->save(false);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'details' => $details,
        ]);
    }

    public function actionDownloadQc($id)
    {
        $model = ProductionRollFormingDetail::findOne($id);
        if (!$model || !$model->document_qc) {
            throw new NotFoundHttpException('Dokumen tidak ditemukan.');
        }

        $filePath = Yii::getAlias('@webroot/uploads/' . $model->document_qc);
        if (file_exists($filePath)) {
            return Yii::$app->response->sendFile($filePath, $model->document_qc);
        } else {
            throw new NotFoundHttpException('File tidak ditemukan di server.');
        }
    }

    /**
     * Updates an existing ProductionRollForming model.
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
     * Deletes an existing ProductionRollForming model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Ambil semua detail terkait
        $details = \app\models\rollforming\ProductionRollFormingDetail::findAll(['id_header' => $model->id]);

        foreach ($details as $detail) {
            // Hapus file dokument QC jika ada
            if ($detail->document_qc) {
                $filePath = Yii::getAlias('@webroot/uploads/') . $detail->document_qc;
                if (file_exists($filePath)) {
                    @unlink($filePath); // Gunakan @ untuk menghindari warning kalau file tidak ada
                }
            }

            // Hapus detail
            $detail->delete();
        }

        // Kembalikan status Working Order
        $workingOrder = \app\models\rollforming\WorkingOrderRollForming::findOne($model->id_worf);
        if ($workingOrder !== null) {
            $workingOrder->status = 1;
            $workingOrder->save(false);
        }

        // Hapus header
        $model->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the ProductionRollForming model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ProductionRollForming the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductionRollForming::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}