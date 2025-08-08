<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\GoodReciept $model */

$this->title = $model->no_good_receipt;
$this->params['breadcrumbs'][] = ['label' => 'Good Reciepts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="good-reciept-view">

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
            'no_good_receipt',
            'id_supplier',
            'no_po',
            'po_date',
            'no_do',
            'receive_date',
        ],
    ]) ?>

    <?php
    // Show DetailGoodReciept table for this GoodReciept
    $details = \app\models\DetailGoodReciept::find()->where(['id_header' => $model->id])->with(['item', 'uom'])->all();
    if ($details) {
        echo '<h3>Detail Items</h3>';
        echo '<div class="table-responsive"><table class="table table-bordered table-striped">';
        echo '<thead><tr>';
        echo '<th>Item</th><th>Thickness</th><th>Width</th><th>Qty</th><th>UOM</th>';
        echo '</tr></thead><tbody>';
        foreach ($details as $detail) {
            echo '<tr>';
            echo '<td>' . ($detail->item ? $detail->item->item_name : '-') . '</td>';
            echo '<td>' . ($detail->thickness ?? '-') . '</td>';
            echo '<td>' . ($detail->width ?? '-') . '</td>';
            echo '<td>' . ($detail->qty ?? '-') . '</td>';
            echo '<td>' . ($detail->uom ? $detail->uom->nama : '-') . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }
    ?>

</div>
