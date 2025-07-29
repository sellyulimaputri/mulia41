<?php

namespace app\controllers\sales;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\master\MasterItem;
use yii\web\NotFoundHttpException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use app\models\master\MasterRawMaterial;
use app\models\sales\SalesOrderStandard;
use app\models\master\MasterTypeProduksi;
use app\models\sales\SalesOrderStandardDetail;
use app\models\sales\SalesOrderStandardSearch;

/**
 * SalesOrderStandardImportController implements the CRUD actions for SalesOrderStandard model.
 */
class SalesOrderStandardImportController extends Controller
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
     * Lists all SalesOrderStandard models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SalesOrderStandardSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SalesOrderStandard model.
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
     * Creates a new SalesOrderStandard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SalesOrderStandard();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $excelFile = UploadedFile::getInstanceByName('excelFile');

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!$model->save()) {
                    throw new \Exception("Gagal simpan header.");
                }

                if (!$excelFile) {
                    throw new \Exception("File Excel tidak ditemukan.");
                }

                $result = $model->importExcelToDetails($excelFile);

                if ($result !== true) {
                    throw new \Exception($result);
                }

                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal simpan data: ' . $e->getMessage());
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing SalesOrderStandard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SalesOrderStandard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Hapus detail dulu
        foreach ($model->salesOrderStandardDetails as $detail) {
            $detail->delete();
        }

        // Hapus header
        $model->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the SalesOrderStandard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return SalesOrderStandard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SalesOrderStandard::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
