<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\rollforming\WorkingOrderRollForming $model */

$this->title = $model->no_planning;
$this->params['breadcrumbs'][] = ['label' => 'Working Order Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="working-order-roll-forming-view">

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
            'no_planning',
            [
                'attribute' => 'id_so',
                'label' => 'Sales Order',
                'value' => function ($model) {
                    return $model->so->no_so;
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
            [
                'label' => 'Status',
                'value' => $model->getStatus(),
            ],

        ],
    ]) ?>
    <h3>Detail Produksi</h3>

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Length (mm)</th>
                <th>Qty SO</th>
                <th>Qty Sudah Produksi</th>
                <th>Qty Produksi Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $details = $model->workingOrderRollFormingDetails;
            foreach ($details as $index => $detail):
                $soDetail = $detail->soDetail;
                $totalProduced = \app\models\rollforming\WorkingOrderRollFormingDetail::find()
                    ->where(['id_so_detail' => $soDetail->id])
                    ->sum('quantity_production');
            ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= Html::encode($soDetail->item->item_name ?? '(tidak ada)') ?></td>
                    <td><?= Html::encode($soDetail->length ?? '-') ?> mm</td>
                    <td><?= $soDetail->qty ?? 0 ?></td>
                    <td><?= $totalProduced ?></td>
                    <td><?= $detail->quantity_production ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    $releases = \app\models\rollforming\ReleaseRawMaterialRollForming::find()
        ->where(['id_worf' => $model->id])
        ->orderBy(['id' => SORT_ASC])
        ->all();
    ?>

    <?php if (!empty($releases)): ?>
        <h3>Data Release Bahan Baku</h3>
        <table class="table table-bordered table-condensed">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No Release</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($releases as $index => $release): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= Html::encode($release->no_release) ?></td>
                        <td>
                            <?= Yii::$app->formatter->asHtml($release->notes) ?>
                        </td>
                        <td>
                            <?= Html::a('View', ['rollforming/release-raw-material-roll-forming/view', 'id' => $release->id], [
                                'class' => 'btn btn-xs btn-info',
                            ]) ?>
                            <?= Html::a('Delete', ['rollforming/release-raw-material-roll-forming/delete', 'id' => $release->id], [
                                'class' => 'btn btn-xs btn-danger',
                                'data' => [
                                    'confirm' => 'Apakah Anda yakin ingin menghapus release ini?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>