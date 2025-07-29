<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\data\ArrayDataProvider;

/** @var yii\web\View $this */
/** @var app\models\sales\SalesOrderStandard $model */

$this->title = $model->no_so;
$this->params['breadcrumbs'][] = ['label' => 'Sales Order Standards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sales-order-standard-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            'no_so',
            'tanggal',
            [
                'attribute' => 'id_customer',
                'value' => function ($model) {
                    return $model->customer ? $model->customer->nama : '(tidak ada)';
                },
            ],

            'deliver_date',
        ],
    ]) ?>

    <h3>Detail Item</h3>

    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->salesOrderStandardDetails,
            'pagination' => false,
        ]),
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id_item',
                'label' => 'Item',
                'value' => function ($detail) {
                    return $detail->item->item_name ?? '(tidak ada)';
                }
            ],
            [
                'attribute' => 'type_produksi',
                'label' => 'Tipe Produksi',
                'value' => function ($model) {
                    return $model->namaTypeProduksi;
                },
            ],

            [
                'attribute' => 'id_raw_material',
                'label' => 'Color',
                'value' => function ($detail) {
                    return $detail->rawMaterial->item_code ?? '-';
                }
            ],
            'description',
            [
                'attribute' => 'length',
                'value' => function ($model) {
                    return $model->length . ' mm';
                },
            ],

            'qty',
            [
                'attribute' => 'harga',
                'format' => ['decimal', 2],
            ],
            [
                'attribute' => 'total',
                'format' => ['decimal', 2],
            ],

        ],
    ]) ?>

</div>