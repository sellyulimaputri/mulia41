<?php

namespace app\controllers;

use app\models\GoodReciept;
use app\models\GoodRecieptSearch;
use app\models\GoodRecieptItemDetail;
use app\models\PurchaseOrder;
use app\models\PoDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * GoodRecieptController implements the CRUD actions for GoodReciept model.
 */
class GoodRecieptController extends Controller
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
     * Lists all GoodReciept models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GoodRecieptSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GoodReciept model.
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
     * Creates a new GoodReciept model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new GoodReciept();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GoodReciept model.
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
     * Deletes an existing GoodReciept model.
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
     * Returns Purchase Orders filtered by supplier as JSON for AJAX requests.
     */
    public function actionGetPoBySupplier($id_supplier)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $orders = PurchaseOrder::find()->where(['id_supplier' => $id_supplier])->all();
        $result = [];
        foreach ($orders as $order) {
            $result[] = [
                'id' => $order->id,
                'no_po' => $order->no_po,
                'tanggal' => $order->tanggal
            ];
        }
        return $result;
    }

    /**
     * Returns PO Details filtered by PO ID as JSON for AJAX requests.
     */
    public function actionGetPoDetails($id_header)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $details = PoDetail::find()->where(['id_header' => $id_header])->with(['item', 'typeCoil', 'uom'])->all();
        $result = [];
        foreach ($details as $detail) {
            $result[] = [
                'id' => $detail->id,
                'item_name' => $detail->item ? $detail->item->item_name : '',
                'type_coil' => $detail->typeCoil ? $detail->typeCoil->nama : '',
                'thickness' => $detail->thickness,
                'width' => $detail->width,
                'qty' => $detail->qty,
                'uom' => $detail->uom ? $detail->uom->nama : '',
                'harga' => $detail->harga,
                'outstanding' => $detail->outstanding
            ];
        }
        return $result;
    }

    /**
     * Finds the GoodReciept model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return GoodReciept the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodReciept::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetPoItemDetails($id_header)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $poItemDetails = GoodRecieptItemDetail::find()->where(['id_header' => $id_header])->all();
        
        if (empty($poItemDetails)) {
            return [
                'success' => false,
                'message' => 'No data found for this item.',
                'data' => []
            ];
        }
        
        $data = [];
        foreach ($poItemDetails as $index => $item) {
            $data[] = [
                'no' => $index + 1,
                'id' => $item->id,
                'id_material' => $item->id_material,
                'supplier_code' => $item->supplier_code,
                'berat_awal' => $item->berat_awal,
                'locater' => $item->locater,
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data
        ];
    }

    /**
     * Handles the Save & Print action from the modal form.
     * @return \yii\web\Response
     */
    public function actionSaveAndPrint()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if ($this->request->isPost) {
            $postData = $this->request->post();
            
            // Validate required data
            if (!isset($postData['po_detail_id']) || empty($postData['po_detail_id'])) {
                return [
                    'success' => false,
                    'message' => 'PoDetail ID is required'
                ];
            }

            // Extract the coil data from the modal form
            $coilData = [];
            if (isset($postData['id_material']) && is_array($postData['id_material'])) {
                for ($i = 0; $i < count($postData['id_material']); $i++) {
                    if (!empty($postData['supplier_code'][$i])) {
                        $coilData[] = [
                            'id_header' => $postData['po_detail_id'],
                            'id_material' => $postData['id_material'][$i],
                            'supplier_code' => $postData['supplier_code'][$i],
                            'berat_awal' => $postData['berat_awal'][$i],
                            'locater' => $postData['locater'][$i],
                        ];
                    }
                }
            }
            
            // Validate that we have coil data
            if (empty($coilData)) {
                return [
                    'success' => false,
                    'message' => 'No valid coil data provided'
                ];
            }
            
            try {
                // Save coil data to PoItemDetail table
                $savedIds = [];
                foreach ($coilData as $coil) {
                    $poItemDetail = new GoodRecieptItemDetail();
                    $poItemDetail->id_header = $coil['id_header'];
                    $poItemDetail->id_material = $coil['id_material'];
                    $poItemDetail->supplier_code = $coil['supplier_code'];
                    $poItemDetail->berat_awal = $coil['berat_awal'];
                    $poItemDetail->locater = $coil['locater'];
                    
                    if ($poItemDetail->save()) {
                        $savedIds[] = $poItemDetail->id;
                        // return [
                        //     'success' => false,
                        //     'message' => 'Yeay'
                        // ];
                    } else {
                        throw new \Exception('Failed to save coil data: ' . json_encode($poItemDetail->errors));
                        return[
                            'success' => false,
                            'message' => $poItemDetail->getErrors()
                        ];
                    }
                }
                
                // Generate PDF and return the URL
                $pdfUrl = Url::to(['good-reciept/generate-pdf', 'id_header' => $postData['po_detail_id']]);
                
                return [
                    'success' => true,
                    'message' => 'Data saved successfully and ready for printing',
                    'pdf_url' => $pdfUrl,
                    'saved_ids' => $savedIds
                ];
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'message' => 'Error saving data: ' . $e->getMessage()
                ];
            }
        }
        
        return [
            'success' => false,
            'message' => 'Invalid request'
        ];
    }

    /**
     * Generates PDF for the saved coil data.
     * @param int $id_header
     * @return \yii\web\Response
     */
    public function actionGeneratePdf($id_header)
    {
        // Get the PoItemDetail data for this header
        $poItemDetails = GoodRecieptItemDetail::find()->where(['id_header' => $id_header])->all();
        if (empty($poItemDetails)) {
            throw new NotFoundHttpException('No data found for this header.');
        }

        // Prepare QR code data (JSON array of coil details)
        $qrData = [];
        foreach ($poItemDetails as $item) {
            $qrData[] = [
                'id_material' => $item->id_material,
                'supplier_code' => $item->supplier_code,
                'berat_awal' => $item->berat_awal,
                'locater' => $item->locater,
            ];
        }
        $qrJson = json_encode($qrData);
        //return urlencode($qrJson);
        // Always use QuickChart API for QR code generation
        $qrImage = 'https://quickchart.io/qr?text=' . urlencode($qrJson);

        // Render a simple HTML page with the QR code
        $html = '<html><head><title>Coil QR Code</title></head><body style="text-align:center;padding:40px;">';
        $html .= '<h2>Coil Detail QR Code</h2>';
        $html .= '<img src="' . $qrImage . '" alt="QR Code" style="width:300px;height:300px;" />';
        $html .= '<br><br><pre style="text-align:left;display:inline-block;">' . htmlspecialchars($qrJson) . '</pre>';
        $html .= '</body></html>';

        return $html;
    }
    /**
     * Prints QR code for a single coil detail row.
     * @param string $id_material
     * @param string $supplier_code
     * @param string $berat_awal
     * @param string $locater
     * @return string
     */
    public function actionPrintQrRow($id_material, $supplier_code, $berat_awal, $locater)
    {
        $coil = [
            'id_material' => $id_material,
            'supplier_code' => $supplier_code,
            'berat_awal' => $berat_awal,
            'locater' => $locater,
        ];
        $qrJson = json_encode($coil);

        // Use endroid/qr-code v3.x to generate QR code image
        $qrImage = 'https://quickchart.io/qr?text=' . urlencode($qrJson);

        // Render a simple HTML page with the QR code
        $html = '<html><head><title>Coil QR Code</title></head><body style="text-align:center;padding:40px;">';
        $html .= '<h2>Coil Detail QR Code</h2>';
        $html .= '<img src="' . $qrImage . '" alt="QR Code" style="width:300px;height:300px;" />';
        $html .= '<br><br><pre style="text-align:left;display:inline-block;">' . htmlspecialchars($qrJson) . '</pre>';
        $html .= '</body></html>';

        return $html;
    }

    public function actionCheckPembatasanBerat($idDetail)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result = [];
        $itemDetail = GoodRecieptItemDetail::find()->where(['id_header' => $idDetail])->all();
        $PoDetail = PoDetail::findOne($idDetail);
        $totalPoDetail = $PoDetail->qty * 1000;
        $total = 0;
        foreach ($itemDetail as $detail) {
            $total += $detail->berat_awal;
        }
        if($total < $totalPoDetail){
            $result['status'] = "success";
            $result['item_code'] = $PoDetail->item->item_code;
            $allItemDetail = GoodRecieptItemDetail::find()->all();
            $result['item_count'] = count($allItemDetail);
            return $result;
        }
        else{
            $result['status'] = "error";
            return $result;
        }
    }

    public function actionGenerateQrRow($id)
    {
        $item = GoodRecieptItemDetail::findOne($id);
        if (!$item) {
            throw new NotFoundHttpException('No data found for this row.');
        }
        $qrJson = json_encode(['id' => $item->id]);
        $qrImage = 'https://quickchart.io/qr?text=' . urlencode($qrJson);
        $html = '<html><head><title>Coil QR Code</title></head><body style="text-align:center;padding:40px;">';
        $html .= '<h2>Coil Row QR Code</h2>';
        $html .= '<img src="' . $qrImage . '" alt="QR Code" style="width:300px;height:300px;" />';
        $html .= '<br><br><pre style="text-align:left;display:inline-block;">' . htmlspecialchars($qrJson) . '</pre>';
        $html .= '</body></html>';
        return $html;
    }
}
