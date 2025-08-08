<?php

use app\models\GoodReciept;
use app\models\BusinessPartner;
use app\models\PurchaseOrder;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\GoodRecieptSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Good Reciepts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="good-reciept-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Good Reciept', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'no_good_receipt',
            [
                'attribute' => 'id_supplier',
                'value' => function($model){
                    $model2 = BusinessPartner::findOne($model->id_supplier);
                    return $model2->nama;
                }
            ],
            [
                'attribute' => 'no_po',
                'label' => 'Nomor PO',
                'value' => function($model){
                    $model2 = PurchaseOrder::findOne($model->no_po);
                    return $model2->no_po;
                }
            ],
            'po_date',
            //'no_do',
            //'receive_date',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {delete}', // no {update}
                'urlCreator' => function ($action, GoodReciept $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
