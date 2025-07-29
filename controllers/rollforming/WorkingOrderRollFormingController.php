<?php

namespace app\controllers\rollforming;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\sales\SalesOrderStandard;
use app\models\rollforming\WorkingOrderRollForming;
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
        $model->no_planning = Yii::$app->request->get('no_planning', '');
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
                // Hapus dulu detail lama
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

        \app\models\rollforming\WorkingOrderRollFormingDetail::deleteAll(['id_header' => $model->id]);

        $model->delete();

        return $this->redirect(['index']);
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