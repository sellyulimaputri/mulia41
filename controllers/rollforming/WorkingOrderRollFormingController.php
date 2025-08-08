<?php

namespace app\controllers\rollforming;

use Yii;
use Mpdf\Mpdf;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Mpdf\Output\Destination;
use yii\web\NotFoundHttpException;
use app\models\rollforming\ProductionRollForming;
use app\models\rollforming\WorkingOrderRollForming;
use app\models\rollforming\ProductionRollFormingDetail;
use app\models\rollforming\WorkingOrderRollFormingDetail;
use app\models\rollforming\WorkingOrderRollFormingSearch;

/**
 * WorkingOrderRollFormingController implements the CRUD actions for WorkingOrderRollForming model.
 */
class WorkingOrderRollFormingController extends Controller
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
     * Lists all WorkingOrderRollForming models.
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


    public function actionCreatePartialQcApprove($id)
    {
        $production = ProductionRollForming::findOne($id);
        if (!$production) {
            throw new NotFoundHttpException("Production not found.");
        }

        $oldWorf = WorkingOrderRollForming::findOne($production->id_worf);
        if (!$oldWorf || $oldWorf->status != 4) {
            Yii::$app->session->setFlash('error', 'Partial QC Approve hanya bisa dilakukan jika status Working Order = 4 (Selesai Produksi).');
            return $this->redirect(['rollforming/working-order-roll-forming/index']);
        }

        $newWorf = new WorkingOrderRollForming();
        $newWorf->no_planning = 'REJ-' . $production->no_production;
        $newWorf->id_so = $production->id_so;
        $newWorf->so_date = $production->so_date;
        $newWorf->production_date = date('Y-m-d');
        $newWorf->type_production = $production->type_production;
        $newWorf->notes = 'Auto-created from reject QC of Production #' . $production->no_production;
        $newWorf->status = 0;

        if (!$newWorf->save()) {
            Yii::$app->session->setFlash('error', 'Failed to create new Working Order.');
            return $this->redirect(['rollforming/production-roll-forming/view', 'id' => $id]);
        }

        $rejectDetails = ProductionRollFormingDetail::find()
            ->where(['id_header' => $production->id])
            ->andWhere(['>', 'reject_qc', 0])
            ->all();

        foreach ($rejectDetails as $detail) {
            $newDetail = new WorkingOrderRollFormingDetail();
            $newDetail->id_header = $newWorf->id;
            $newDetail->id_so_detail = $detail->worfDetail->id_so_detail ?? null;
            $newDetail->quantity_production = $detail->reject_qc;

            if (!$newDetail->save()) {
                Yii::$app->session->addFlash('error', 'Failed saving detail item.');
            }
        }
        $oldWorf = WorkingOrderRollForming::findOne($production->id_worf);
        if ($oldWorf && $oldWorf->status == 4) {
            if ($production->status == 1) {
                $oldWorf->status = 3;
            } elseif ($production->status == 0) {
                $oldWorf->status = 2;
            }
            $oldWorf->save(false);
        }

        Yii::$app->session->setFlash('success', 'Partial QC approved. New Working Order created.');
        return $this->redirect(['rollforming/working-order-roll-forming/view', 'id' => $newWorf->id]);
    }

    /**
     * Displays a single WorkingOrderRollForming model.
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
     * Creates a new WorkingOrderRollForming model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id_so = null)
    {
        $model = new WorkingOrderRollForming();
        $model->type_production = 1;
        $model->no_planning = WorkingOrderRollForming::generateNoPlanning();        
        // $model->no_planning = Yii::$app->request->get('no_planning', '');
        $model->production_date = Yii::$app->request->get('production_date', '');

        if ($id_so !== null) {
            $model->id_so = $id_so;
            $so = \app\models\sales\SalesOrderStandard::findOne($id_so);
            if ($so) {
                $model->so_date = $so->tanggal;
                $soDetails = $so->salesOrderStandardDetails;

                foreach ($soDetails as $detail) {
                    $detail->remaining_qty = WorkingOrderRollForming::getRemainingQty($detail->id);
                }
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $qtyProductions = Yii::$app->request->post('qty_production', []);
            $errors = WorkingOrderRollForming::validateQtyProductions($qtyProductions);

            if (!empty($errors)) {
                Yii::$app->session->setFlash('error', implode('<br>', $errors));
            } elseif ($model->save()) {
                $model->saveDetails($qtyProductions);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'soDetails' => $soDetails ?? [],
        ]);
    }

    /**
     * Updates an existing WorkingOrderRollForming model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Cegah edit jika status ≠ 0
        if ($model->status != 0) {
            Yii::$app->session->setFlash('error', 'Data tidak bisa diedit karena status status sudah Direlease');
            return $this->redirect(['index']);
        }

        $so = \app\models\sales\SalesOrderStandard::findOne($model->id_so);
        $soDetails = $so ? $so->salesOrderStandardDetails : [];

        foreach ($soDetails as $detail) {
            $detail->remaining_qty = WorkingOrderRollForming::getRemainingQty($detail->id, $model->id);
        }

        if ($model->load(Yii::$app->request->post())) {
            $qtyProductions = Yii::$app->request->post('qty_production', []);
            $errors = WorkingOrderRollForming::validateQtyProductions($qtyProductions, $model->id);

            if (!empty($errors)) {
                Yii::$app->session->setFlash('error', implode('<br>', $errors));
            } elseif ($model->save()) {
                \app\models\rollforming\WorkingOrderRollFormingDetail::deleteAll(['id_header' => $model->id]);
                $model->saveDetails($qtyProductions);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'soDetails' => $soDetails,
        ]);
    }

    /**
     * Deletes an existing WorkingOrderRollForming model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Cek apakah WO ini adalah hasil dari reject (misalnya dari prefix no_planning)
        if (strpos($model->no_planning, 'REJ-') === 0) {
            // Ambil nomor produksi dari no_planning: 'REJ-PRD0001' => 'PRD0001'
            $originalProductionNo = substr($model->no_planning, 4);
            $originalProduction = \app\models\rollforming\ProductionRollForming::find()
                ->where(['no_production' => $originalProductionNo])
                ->one();

            if ($originalProduction && $originalProduction->id_worf) {
                $originalWorf = WorkingOrderRollForming::findOne($originalProduction->id_worf);
                if ($originalWorf && $originalWorf->status == 2 || $originalWorf->status == 3) {
                    $originalWorf->status = 4;
                    $originalWorf->save(false);
                }
            }
        }

        // Hapus semua detail
        \app\models\rollforming\WorkingOrderRollFormingDetail::deleteAll(['id_header' => $model->id]);

        // Hapus header
        $model->delete();

        Yii::$app->session->setFlash('success', 'Working Order berhasil dihapus.');
        return $this->redirect(['index']);
    }

    public function actionPrint($id)
    {
        $model = $this->findModel($id);

        $content = $this->renderPartial('_print_working_order', [
            'model' => $model,
        ]);

        $pdf = new Mpdf([
            'format' => 'A4',
            'orientation' => 'L',
        ]);

        $pdf->WriteHTML($content);

        return $pdf->Output("Working-Order-{$model->no_planning}.pdf", Destination::INLINE);
    }

    /**
     * Finds the WorkingOrderRollForming model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return WorkingOrderRollForming the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkingOrderRollForming::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}