<?php

namespace app\controllers;

use app\models\PurchaseOrder;
use app\models\PurchaseOrderSearch;
use app\models\PoDetail;
use app\models\PoItemDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PurchaseOrderController implements the CRUD actions for PurchaseOrder model.
 */
class PurchaseOrderController extends Controller{
    /**
     * Returns Purchase Orders filtered by supplier as JSON for AJAX requests.
     */
    public function actionListBySupplier($id_supplier)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $orders = PurchaseOrder::find()->where(['id_supplier' => $id_supplier])->all();
        $result = [];
        foreach ($orders as $order) {
            $result[] = [
                'id' => $order->id,
                'no_po' => $order->no_po
            ];
        }
        return $result;
    }
    
    /**
     * Returns category id for a given item id (MasterRawMaterial) as JSON for AJAX requests.
     */
    public function actionGetCategoryByItem($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $item = \app\models\MasterRawMaterial::findOne($id);
        return ['category_id' => $item ? $item->item_category : null];
    }

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
     * Lists all PurchaseOrder models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseOrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PurchaseOrder model.
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
     * Creates a new PurchaseOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PurchaseOrder();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                //return "<pre>".print_r($this->request->post() , true)."</pre>";
                if($model->save()){
                    foreach ($this->request->post('PurchaseOrder')['detailItems'] as $key => $value) {
                        $modelDetail = new PoDetail();
                        $modelDetail->id_item = $value['item'];
                        if(isset($value['thickness']) && $value['thickness'] != null){
                            $modelDetail->thickness = $value['thickness']; 
                            $modelDetail->width = $value['width'];
                            $modelDetail->id_type_coil = $value['type_coil'];
                        }
                        $modelDetail->qty = $value['qty'];
                        $modelDetail->id_uom = $value['uom'];
                        $modelDetail->harga = $value['price'];
                        $modelDetail->outstanding = $value['total'];
                        $modelDetail->id_header = $model->id;
                        if($modelDetail->save()){

                        }
                        else{
                            return var_dump($modelDetail->getErrors());
                        }
                    }
                    return $this->redirect(['index']);
                }
                else{
                    return var_dump($model->getErrors());
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PurchaseOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Load existing PoDetail into detailItems for the form
        $model->detailItems = [];
        $details = PoDetail::find()->where(['id_header' => $model->id])->all();
        foreach ($details as $detail) {
            $model->detailItems[] = [
                'item' => $detail->id_item,
                'category' => $detail->item ? $detail->item->id_category ?? null : null, // if you have category relation
                'type_coil' => $detail->id_type_coil,
                'thickness' => $detail->thickness,
                'width' => $detail->width,
                'qty' => $detail->qty,
                'uom' => $detail->id_uom,
                'price' => $detail->harga,
                'total' => $detail->outstanding,
                'id' => $detail->id, // for update/delete tracking
            ];
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->save()) {
                $postedDetails = $this->request->post('PurchaseOrder')['detailItems'];
                $existingDetails = PoDetail::find()->where(['id_header' => $model->id])->indexBy('id')->all();
                $usedIds = [];

                foreach ($postedDetails as $row) {
                    if (!empty($row['id']) && isset($existingDetails[$row['id']])) {
                        // Update existing
                        $detail = $existingDetails[$row['id']];
                        $usedIds[] = $row['id'];
                    } else {
                        // New detail
                        $detail = new PoDetail();
                        $detail->id_header = $model->id;
                    }
                    $detail->id_item = $row['item'] ?? null;
                    $detail->id_type_coil = $row['type_coil'] ?? null;
                    $detail->thickness = $row['thickness'] ?? null;
                    $detail->width = $row['width'] ?? null;
                    $detail->qty = $row['qty'] ?? null;
                    $detail->id_uom = $row['uom'] ?? null;
                    $detail->harga = $row['price'] ?? null;
                    $detail->outstanding = $row['total'] ?? null;
                    $detail->save(false);
                }

                // Delete removed details
                foreach ($existingDetails as $id => $detail) {
                    if (!in_array($id, $usedIds)) {
                        $detail->delete();
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PurchaseOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PurchaseOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PurchaseOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PurchaseOrder::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetPoDate($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $po = \app\models\PurchaseOrder::findOne($id);
        return ['tanggal' => $po ? $po->tanggal : null];
    }
}
    /**
     * Returns Purchase Orders filtered by supplier as JSON for AJAX requests.
     */
    // public function actionListBySupplier($id_supplier)
    // {
    //     \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //     $orders = PurchaseOrder::find()->where(['id_supplier' => $id_supplier])->all();
    //     $result = [];
    //     foreach ($orders as $order) {
    //         $result[] = [
    //             'id' => $order->id,
    //             'no_po' => $order->no_po
    //         ];
    //     }
    //     return $result;
    // }
