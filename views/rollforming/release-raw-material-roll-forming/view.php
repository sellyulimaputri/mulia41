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
                'value' => function ($model) {
                    return $model->so->no_so ?? '-';
                },
            ],
            [
                'attribute' => 'id_worf',
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
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Raw Material</th>
                <th>Length (mm)</th>
                <th>Qty Produksi</th>
                <th>Reference Max Release</th>
                <th>QR Scan</th>
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
                <td>
                    <?php if (!empty($detail->qrs)): ?>
                    <?php
                            $modalId = "modal-qr-" . md5($detail->id);
                            ?>
                    <?= Html::button('View', [
                                'class' => 'btn btn-info btn-sm',
                                'data-toggle' => 'modal',
                                'data-target' => "#$modalId"
                            ]) ?>

                    <!-- Modal -->
                    <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" role="dialog"
                        aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="<?= $modalId ?>Label">
                                        Detail Scan QR
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>No GR</th>
                                                <th>Material</th>
                                                <th>Supplier Code</th>
                                                <th>Berat Awal</th>
                                                <th>Locater</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($detail->qrs as $qr): ?>
                                            <?php
                                                        $itemDetail = $qr->scanResult;
                                                        $noGr = $itemDetail->header->no_good_receipt ?? 'GR#' . $qr->id_scan_result;
                                                        ?>
                                            <tr>
                                                <td><?= $noGr ?></td>
                                                <td><?= $itemDetail->id_material ?? '-' ?></td>
                                                <td><?= $itemDetail->supplier_code ?? '-' ?></td>
                                                <td><?= $itemDetail->berat_awal ?? '-' ?></td>
                                                <td><?= $itemDetail->locater ?: '(Kosong)' ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <em class="text-muted">Tidak ada scan</em>
                    <?php endif; ?>
                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>