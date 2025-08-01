<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\rollforming\ProductionRollForming $model */

$this->title = $model->no_production;
$this->params['breadcrumbs'][] = ['label' => 'Production Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="production-roll-forming-view">
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
            'no_production',
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
    <h3>Detail Produksi</h3>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Length</th>
                <th>Description</th>
                <th>Tipe Produksi</th>
                <th>Color (Code)</th>
                <th>Qty Order</th>
                <th>Remaining Qty</th>
                <th>Qty Produksi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->productionRollFormingDetails as $index => $detail):
                $soDetail = $detail->worfDetail->soDetail ?? null;
                $item = $soDetail->item ?? null;
            ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= Html::encode($item->item_name ?? '-') ?></td>
                    <td><?= Html::encode($soDetail->length ?? '-') ?></td>
                    <td><?= Html::encode($soDetail->description ?? '-') ?></td>
                    <td><?= Html::encode($soDetail->namaTypeProduksi ?? '-') ?></td>
                    <td><?= Html::encode($soDetail->rawMaterial->item_code ?? '-') ?></td>
                    <td><?= Html::encode($soDetail->qty ?? '-') ?></td>
                    <td><?= Html::encode($soDetail->remaining_qty ?? '-') ?></td>
                    <td><?= Html::encode($detail->worfDetail->quantity_production ?? '-') ?></td>
                    <td>
                        <button type="button" class="btn btn-primary btn-production" data-id="<?= $detail->id ?>"
                            data-name="<?= $item->item_name ?? '-' ?>" data-actual="<?= $detail->actual_production_date ?>"
                            data-final="<?= $detail->final_result ?>" data-waste="<?= $detail->waste ?>"
                            data-punch="<?= $detail->punch_scrap ?>" data-refurbish="<?= $detail->refurbish ?>"
                            data-remaining="<?= $detail->remaining_coil ?>" data-toggle="modal"
                            data-target="#productionModal">
                            Production
                        </button>

                        <button type="button" class="btn btn-warning btn-qc" data-id="<?= $detail->id ?>"
                            data-name="<?= $item->item_name ?? '-' ?>" data-qc-final="<?= $detail->final_result_qc ?>"
                            data-qc-reject="<?= $detail->reject_qc ?>" data-sample1="<?= $detail->sample_result_1_qc ?>"
                            data-sample2="<?= $detail->sample_result_2_qc ?>"
                            data-sample3="<?= $detail->sample_result_3_qc ?>"
                            data-sample4="<?= $detail->sample_result_4_qc ?>"
                            data-actual="<?= $detail->actual_production_date ?>" data-final="<?= $detail->final_result ?>"
                            data-waste="<?= $detail->waste ?>" data-punch="<?= $detail->punch_scrap ?>"
                            data-refurbish="<?= $detail->refurbish ?>" data-remaining="<?= $detail->remaining_coil ?>"
                            data-document="<?= $detail->document_qc ?>" data-toggle="modal" data-target="#qcModal">
                            QC
                        </button>

                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php

    $costProductions = \app\models\rollforming\CostProductionRollForming::find()
        ->where(['id_production' => $model->id])
        ->all();
    if (!empty($costProductions)):

    ?>
        <h3>Data Biaya Produksi</h3>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No Production</th>
                    <th>No Working Order</th>
                    <th>No Sales Order</th>
                    <th>Tanggal SO</th>
                    <th>Tanggal Produksi</th>
                    <th>Tipe Produksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($costProductions as $i => $cost):
                ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= Html::encode($cost->production->no_production ?? '-') ?></td>
                        <td><?= Html::encode($cost->worf->no_planning ?? '-') ?></td>
                        <td><?= Html::encode($cost->so->no_so ?? '-') ?></td>
                        <td><?= Yii::$app->formatter->asDate($cost->so_date) ?></td>
                        <td><?= Yii::$app->formatter->asDate($cost->production_date) ?></td>
                        <td><?= Html::encode($cost->namaTypeProduction ?? '-') ?></td>
                        <td>
                            <?= Html::a('View', ['rollforming/cost-production-roll-forming/view', 'id' => $cost->id], ['class' => 'btn btn-sm btn-info']) ?>
                            <?= Html::a('Delete', ['rollforming/cost-production-roll-forming/delete', 'id' => $cost->id], [
                                'class' => 'btn btn-sm btn-danger',
                                'data' => [
                                    'confirm' => 'Apakah Anda yakin ingin menghapus cost production ini?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    </tr>
            <?php endforeach;
            endif;
            ?>
            </tbody>
        </table>

        <?php
        \yii\bootstrap4\Modal::begin([
            'title' => '<h5 class="modal-title" id="productionModalLabel"></h5>',
            'id' => 'productionModal',
            'size' => 'modal-xl',
            'footer' => false,
        ]);
        ?>

        <div class="modal-body">
            <input type="hidden" id="modal-id" name="modal-id">

            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="modal-actual-production-date" class="form-label">Actual Production Date</label>
                    <input type="date" class="form-control" id="modal-actual-production-date" readonly>
                </div>

                <div class="col-md-2">

                    <label for="modal-final-result" class="form-label">Final Result</label>
                    <input type="number" class="form-control" id="modal-final-result" readonly>
                </div>

                <div class="col-md-2">

                    <label for="modal-waste" class="form-label">Waste</label>
                    <input type="number" class="form-control" id="modal-waste" readonly>
                </div>

                <div class="col-md-2">

                    <label for="modal-punch-scrap" class="form-label">Punch Scrap</label>
                    <input type="number" class="form-control" id="modal-punch-scrap" readonly>
                </div>

                <div class="col-md-2">

                    <label for="modal-refurbish" class="form-label">Refurbish</label>
                    <input type="number" class="form-control" id="modal-refurbish" readonly>
                </div>

                <div class="col-md-2">

                    <label for="modal-remaining-coil" class="form-label">Remaining Coil</label>
                    <input type="number" class="form-control" id="modal-remaining-coil" readonly>
                </div>
            </div>
        </div>

        <?php \yii\bootstrap4\Modal::end(); ?>
        <?php \yii\bootstrap4\Modal::begin([
            'title' => '<h5 class="modal-title" id="qcModalLabel"></h5>',
            'id' => 'qcModal',
            'size' => 'modal-xl',
            'footer' => false,
        ]); ?>

        <div class="modal-body">
            <input type="hidden" id="qc-id">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label>Actual Prod. Date</label>
                    <input type="text" class="form-control" id="qc-actual-date" readonly>
                </div>
                <div class="col-md-2">
                    <label>Final Result</label>
                    <input type="number" class="form-control" id="qc-final-prod" readonly>
                </div>
                <div class="col-md-2">
                    <label>Waste</label>
                    <input type="number" class="form-control" id="qc-waste" readonly>
                </div>
                <div class="col-md-2">
                    <label>Punch Scrap</label>
                    <input type="number" class="form-control" id="qc-punch" readonly>
                </div>
                <div class="col-md-2">
                    <label>Refurbish</label>
                    <input type="number" class="form-control" id="qc-refurbish" readonly>
                </div>
                <div class="col-md-2">
                    <label>Remaining Coil</label>
                    <input type="number" class="form-control" id="qc-remaining-coil" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="qc-final-result">Final Result QC</label>
                    <input type="number" class="form-control" id="qc-final-result" readonly>
                </div>
                <div class="col-md-3">
                    <label for="qc-reject">Reject QC</label>
                    <input type="number" class="form-control" id="qc-reject" readonly>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="qc-document" class="font-weight-bold">Dokumen QC</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="qc-document" readonly placeholder="Tidak ada file">
                            <div class="input-group-append">
                                <a id="qc-document-link" href="#" target="_blank" class="btn btn-secondary"
                                    style="display: none;">
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mb-3">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="col-md-3">
                        <label for="qc-sample-<?= $i ?>">Sample <?= $i ?> QC</label>
                        <input type="number" class="form-control" id="qc-sample-<?= $i ?>" readonly>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <?php \yii\bootstrap4\Modal::end(); ?>


</div>
<?php
$js = <<<JS
$('.btn-production').on('click', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');
    var actual = $(this).data('actual');
    var final = $(this).data('final');
    var waste = $(this).data('waste');
    var punch = $(this).data('punch');
    var refurbish = $(this).data('refurbish');
    var remaining = $(this).data('remaining');

    $('#modal-id').val(id);
    $('#modal-actual-production-date').val(actual);
    $('#modal-final-result').val(final);
    $('#modal-waste').val(waste);
    $('#modal-punch-scrap').val(punch);
    $('#modal-refurbish').val(refurbish);
    $('#modal-remaining-coil').val(remaining);

    // Ganti judul modal
    $('#productionModalLabel').text('Form Roll Forming ' + name);
});
$('.btn-qc').on('click', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');
    var actual = $(this).data('actual');
    var final = $(this).data('final');
    var waste = $(this).data('waste');
    var punch = $(this).data('punch');
    var refurbish = $(this).data('refurbish');
    var remaining = $(this).data('remaining');


    $('#qc-id').val(id);
    $('#qcModalLabel').text('Form QC untuk ' + name);

    $('#qc-final-result').val($(this).data('qc-final'));
    $('#qc-reject').val($(this).data('qc-reject'));
    $('#qc-sample-1').val($(this).data('sample1'));
    $('#qc-sample-2').val($(this).data('sample2'));
    $('#qc-sample-3').val($(this).data('sample3'));
    $('#qc-sample-4').val($(this).data('sample4'));
    $('#qc-document').val($(this).data('document'));
    
    $('#qc-actual-date').val(actual);
    $('#qc-final-prod').val(final);
    $('#qc-waste').val(waste);
    $('#qc-punch').val(punch);
    $('#qc-refurbish').val(refurbish);
    $('#qc-remaining-coil').val(remaining);
    var documentQc = $(this).data('document');

    if (documentQc && id) {
        const downloadUrl = baseUrl + '/rollforming/production-roll-forming/download-qc?id=' + id;
        $('#qc-document-link').attr('href', downloadUrl).show();
    } else {
        $('#qc-document-link').hide();
    }



});
JS;


$this->registerJs($js, \yii\web\View::POS_READY);
?>