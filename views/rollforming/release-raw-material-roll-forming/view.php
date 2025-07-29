<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\rollforming\ReleaseRawMaterialRollForming $model */

$this->title = $model->no_release;
$this->params['breadcrumbs'][] = ['label' => 'Release Raw Material Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="release-raw-material-roll-forming-view">

    <p>
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
            'no_release',
            [
            'attribute' => 'id_so',
            'label' => 'No SO',
            'value' => function ($model) {
                return $model->so->no_so ?? '-';
            },
        ],
        [
            'attribute' => 'id_worf',
            'label' => 'No SPK',
            'value' => function ($model) {
                return $model->worf->no_planning ?? '-';
            },
        ],
            'so_date',
            'worf_date',
            [
                'attribute' => 'type_production',
                'label' => 'Tipe Produksi',
                'value' => function ($model) {
                    return $model->namaTypeProduction;
                },
            ],
            [
                'attribute' => 'notes',
                'format' => 'raw',
            ],
        ],
    ]) ?>
    <h4>Detail Bahan Baku</h4>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Raw Material</th>
                <th>Length (mm)</th>
                <th>Qty Produksi</th>
                <th>Reference Max Release</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->releaseRawMaterialRollFormingDetails as $i => $detail): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $detail->worfDetail->soDetail->item->item_name ?? '-' ?></td>
                <td><?= $detail->rawMaterial->item_name ?? '-' ?></td>
                <td><?= $detail->worfDetail->soDetail->length ?? '-' ?></td>
                <td><?= $detail->worfDetail->quantity_production ?? '-' ?></td>
                <td><?= $detail->reference_max_release ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>