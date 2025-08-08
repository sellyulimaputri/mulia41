<?php

namespace app\controllers\rollforming;

use Yii;
use yii\web\Controller;
use app\components\Model;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\rollforming\ProductionRollForming;
use app\models\rollforming\CostProductionRollForming;
use app\models\rollforming\ProductionRollFormingSearch;
use app\models\rollforming\CostProductionRollFormingDetail;
use app\models\rollforming\CostProductionRollFormingSearch;

/**
 * CostProductionRollFormingController implements the CRUD actions for CostProductionRollForming model.
 */
class CostProductionRollFormingController extends Controller
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
     * Lists all CostProductionRollForming models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductionRollFormingSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CostProductionRollForming model.
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
     * Creates a new CostProductionRollForming model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id_production = null)
    {
        if ($id_production) {
            $production = ProductionRollForming::findOne($id_production);

            if (!$production || $production->status != 0) {
                Yii::$app->session->setFlash('error', 'Sudah ada cost production untuk produksi ini.');
                return $this->redirect(['index']);
            }
        }
        $model = new CostProductionRollForming();
        $details = [new CostProductionRollFormingDetail()];

        if ($id_production !== null) {
            $production = \app\models\rollforming\ProductionRollForming::findOne($id_production);
            if ($production !== null) {
                $model->id_production = $production->id;
                $model->id_so = $production->id_so;
                $model->id_worf = $production->id_worf;
                $model->so_date = $production->so_date;
                $model->production_date = $production->production_date;
                $model->type_production = $production->type_production;
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $details = Model::createMultiple(CostProductionRollFormingDetail::class);
            Model::loadMultiple($details, Yii::$app->request->post());

            $model->save();
            foreach ($details as $detail) {
                $detail->id_header = $model->id;
                $detail->save();
            }
            $production = ProductionRollForming::findOne($id_production);
            if ($production !== null) {
                $production->status = 1;
                $production->save(false);
            }
            if ($model->id_worf) {
                $workingOrder = \app\models\rollforming\WorkingOrderRollForming::findOne($model->id_worf);
                if ($workingOrder !== null) {
                    if ($workingOrder->status == 2) {
                        $workingOrder->status = 3;
                        $workingOrder->save(false);
                    }
                    // Kalau statusnya 4, tidak diubah (tetap 4)
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'details' => $details,
        ]);
    }

    /**
     * Updates an existing CostProductionRollForming model.
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
     * Deletes an existing CostProductionRollForming model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Hapus semua detail terlebih dahulu
        foreach ($model->costProductionRollFormingDetails as $detail) {
            $detail->delete();
        }

        $production = ProductionRollForming::findOne($model->id_production);
        if ($production !== null) {
            $production->status = 0;
            $production->save(false);
        }
        if ($model->id_worf) {
            $workingOrder = \app\models\rollforming\WorkingOrderRollForming::findOne($model->id_worf);
            if ($workingOrder !== null) {
                if ($workingOrder->status == 3) {
                    $workingOrder->status = 2;
                    $workingOrder->save(false);
                }
                // Kalau statusnya 4, tidak diubah (tetap 4)
            }
        }

        // Lalu hapus header-nya
        $model->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the CostProductionRollForming model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CostProductionRollForming the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CostProductionRollForming::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
