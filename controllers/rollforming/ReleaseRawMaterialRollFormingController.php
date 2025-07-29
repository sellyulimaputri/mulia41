<?php

namespace app\controllers\rollforming;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\rollforming\WorkingOrderRollForming;
use app\models\rollforming\ReleaseRawMaterialRollForming;
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
        $searchModel = new ReleaseRawMaterialRollFormingSearch();
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
        if ($id_worf && ReleaseRawMaterialRollForming::isAlreadyReleased($id_worf)) {
            Yii::$app->session->setFlash('error', 'Data sudah direlease, tidak bisa membuat release baru.');
            return $this->redirect(['index']);
        }

        $model = new ReleaseRawMaterialRollForming(['id_worf' => $id_worf]);
        $model->initializeFromWorkingOrder();

        $details = $model->getWorfDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->saveDetailReleases(Yii::$app->request->post('Detail', []));
            return $this->redirect(['view', 'id' => $model->id]);
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

        \app\models\rollforming\ReleaseRawMaterialRollFormingDetail::deleteAll(['id_header' => $model->id]);

        $model->delete();

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
}
