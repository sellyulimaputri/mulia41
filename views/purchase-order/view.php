<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PurchaseOrder $model */

$this->title = $model->no_po;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-order-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'no_po',
            'tanggal',
            [
                'label' => 'Supplier',
                'attribute' => 'id_supplier',
                'value' => function($model) {
                    // If $model->id_supplier is an object (relation), get nama
                    if (is_object($model->id_supplier)) {
                        return $model->supplier->nama ?? '(not set)';
                    }
                    // If $model->uom is an id, fetch the related model
                    $SupplierModel = \app\models\BusinessPartner::findOne($model->id_supplier);
                    return $SupplierModel ? $SupplierModel->nama : '(not set)';
                },
            ],
        ],
    ]) ?>

    <?php
    // Show PoDetail table for this PurchaseOrder
    $poDetails = \app\models\PoDetail::find()->where(['id_header' => $model->id])->with(['item', 'typeCoil', 'uom'])->all();
    if ($poDetails) {
        echo '<h3>Detail Items</h3>';
        echo '<div class="table-responsive"><table class="table table-bordered table-striped">';
        echo '<thead><tr>';
        echo '<th>Item</th><th>Type Coil</th><th>Thickness</th><th>Width</th><th>Qty</th><th>UOM</th><th>Price</th><th>Outstanding</th>';
        echo '</tr></thead><tbody>';
        foreach ($poDetails as $detail) {
            echo '<tr>';
            echo '<td>' . ($detail->item ? $detail->item->item_name : '-') . '</td>';
            echo '<td>' . ($detail->typeCoil ? $detail->typeCoil->nama : '-') . '</td>';
            echo '<td>' . ($detail->thickness ?? '-') . '</td>';
            echo '<td>' . ($detail->width ?? '-') . '</td>';
            echo '<td>' . ($detail->qty ?? '-') . '</td>';
            echo '<td>' . ($detail->uom ? $detail->uom->nama : '-') . '</td>';
            echo '<td>' . (number_format($detail->harga) ?? '-') . '</td>';
            echo '<td>' . (number_format($detail->outstanding) ?? '-') . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }
    ?>

</div>
