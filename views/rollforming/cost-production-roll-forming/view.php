<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\rollforming\CostProductionRollForming $model */

$this->title = $model->production->no_production;
$this->params['breadcrumbs'][] = ['label' => 'Cost Production Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cost-production-roll-forming-view">
    <p>
        <!-- <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?> -->
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
            [
                'attribute' => 'id_production',
                'label' => 'No Production',
                'value' => function ($model) {
                    return $model->production->no_production ?? '-';
                },
            ],
            [
                'attribute' => 'id_worf',
                'label' => 'No Working Order',
                'value' => function ($model) {
                    return $model->worf->no_planning ?? '-';
                },
            ],
            [
                'attribute' => 'id_so',
                'label' => 'No Sales Order',
                'value' => function ($model) {
                    return $model->so->no_so ?? '-';
                },
            ],
            'so_date',
            'production_date',
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
    <h4>Detail Biaya Produksi</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Deskripsi</th>
                <th>Nominal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->costProductionRollFormingDetails as $i => $detail): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $detail->description ?></td>
                    <td><?= Yii::$app->formatter->asCurrency($detail->nominal, 'IDR') ?></td>
                    <td><?= nl2br(Html::encode($detail->notes)) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>