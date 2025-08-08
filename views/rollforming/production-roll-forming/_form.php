<?php

use yii\helpers\Html;
use yii\bootstrap4\Modal;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\rollforming\ProductionRollForming $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="production-roll-forming-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'no_production')->textInput(['maxlength' => true, 'placeholder' => 'Enter No Production']) ?>
        </div>

        <div class="col-md-2">
            <label class="control-label">Nomor SO</label>
            <input type="text" class="form-control" value="<?= $model->so ? $model->so->no_so : '' ?>" readonly>
            <?= $form->field($model, 'id_so')->hiddenInput()->label(false) ?>
        </div>

        <div class="col-md-2">
            <label class="control-label">Working Order</label>
            <input type="text" class="form-control" value="<?= $model->worf ? $model->worf->no_planning : '' ?>"
                readonly>
            <?= $form->field($model, 'id_worf')->hiddenInput()->label(false) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'so_date')->textInput(['readonly' => true]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'production_date')->input('date') ?>
        </div>
        <div class="col-md-2">
            <label class="control-label">Type Production</label>
            <input type="text" class="form-control" value="<?= $model->getNamaTypeProduction() ?>" readonly>
            <?= $form->field($model, 'type_production')->hiddenInput()->label(false) ?>
        </div>
    </div>

    <h4>Detail Material</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Length (mm)</th>
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
            <?php foreach ($details as $i => $detail): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $detail->soDetail->item->item_name ?? '-' ?></td>
                    <td><?= $detail->soDetail->length ?? '-' ?></td>
                    <td><?= $detail->soDetail->description ?? '-' ?></td>
                    <td><?= $detail->soDetail->namaTypeProduksi ?? '-' ?></td>
                    <td><?= $detail->soDetail->rawMaterial->item_code ?? '-' ?></td>
                    <td><?= $detail->soDetail->qty ?? '-' ?></td>
                    <td><?= $detail->soDetail->remaining_qty ?? '-' ?></td>
                    <td><?= $detail->quantity_production ?? '-' ?></td>
                    <td>
                        <!-- <div class="btn-group"> -->
                        <button type="button" class="btn btn-primary btn-production" data-toggle="modal"
                            data-target="#productionModal" data-id="<?= $detail->id ?>"
                            data-name="<?= $detail->soDetail->item->item_name ?? '-' ?>"
                            data-qty_production="<?= $detail->quantity_production ?>">
                            Production
                        </button>

                        <button type="button" class="btn btn-warning btn-qc" data-toggle="modal" data-target="#qcModal"
                            data-id="<?= $detail->id ?>" data-name="<?= $detail->soDetail->item->item_name ?? '-' ?>">
                            QC
                        </button>

                        <button type="button" class="btn btn-secondary btn-upload-file" data-id="<?= $detail->id ?>">
                            Upload Dokumen QC
                        </button>


                        <!-- </div> -->
                    </td>

                </tr>
                <input type="date" class="form-control" name="actual_production_date[<?= $detail->id ?>]"
                    style="display:none;">
                <input type="number" class="form-control" name="final_result[<?= $detail->id ?>]" style="display:none;">
                <input type="number" class="form-control" name="waste[<?= $detail->id ?>]" style="display:none;"
                    step="0.01">
                <input type="number" class="form-control" name="punch_scrap[<?= $detail->id ?>]" style="display:none;"
                    step="0.01">

                <input type="number" class="form-control" name="refurbish[<?= $detail->id ?>]" style="display:none;"
                    step="0.01">
                <input type="number" class="form-control" name="remaining_coil[<?= $detail->id ?>]" style="display:none;"
                    step="0.01">

                <input type="number" class="form-control" name="final_result_qc[<?= $detail->id ?>]" style="display:none;">
                <input type="number" class="form-control" name="reject_qc[<?= $detail->id ?>]" style="display:none;">
                <!-- Hidden file input untuk QC, agar tersubmit -->
                <input type="file" name="document_qc[<?= $detail->id ?>]" class="document-upload-hidden"
                    id="hidden-file-<?= $detail->id ?>" style="display:none;">
                <?php for ($set = 1; $set <= 6; $set++): ?>
                    <?php for ($sample = 1; $sample <= 4; $sample++): ?>
                        <input type="number" class="form-control"
                            name="sample_result_<?= $sample ?>_qc_<?= $set ?>[<?= $detail->id ?>]" style="display:none;">
                    <?php endfor; ?>
                <?php endfor; ?>

                <input type="hidden" name="Detail[<?= $i ?>][id_worf_detail]" value="<?= $detail->id ?>" />
            <?php endforeach; ?>
        </tbody>

        </tbody>
    </table>


    <?= $form->field($model, 'notes')->widget(\dosamigos\ckeditor\CKEditor::class, [
        'options' => ['rows' => 6],
        'preset' => 'standard',
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php \yii\bootstrap4\Modal::begin([
        'title' => '<h5 class="modal-title" id="productionModalLabel"></h5>', // kosong, nanti diisi JS
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
                <input type="number" class="form-control" id="modal-final-result" placeholder="Enter Final Result"
                    max="...">
            </div>

            <div class="col-md-2">
                <label for="modal-waste" class="form-label">Waste (kg)</label>
                <input type="number" class="form-control" id="modal-waste" placeholder="Enter Waste" step="0.01">
            </div>

            <div class="col-md-2">
                <label for="modal-punch-scrap" class="form-label">Punch Scrap (kg)</label>
                <input type="number" class="form-control" id="modal-punch-scrap" placeholder="Enter Punch Scrap"
                    step="0.01">
            </div>

            <div class="col-md-2">
                <label for="modal-refurbish" class="form-label">Refurbish (kg)</label>
                <input type="number" class="form-control" id="modal-refurbish" placeholder="Enter Refurbish"
                    step="0.01">
            </div>

            <div class="col-md-2">
                <label for="modal-remaining-coil" class="form-label">Remaining Coil (kg)</label>
                <input type="number" class="form-control" id="modal-remaining-coil" placeholder="Enter Remaining Coil"
                    step="0.01">
            </div>
        </div>
        <button type="button" class="btn btn-success" id="saveProductionDetail">Save</button>
    </div>

    <?php \yii\bootstrap4\Modal::end(); ?>

    <?php \yii\bootstrap4\Modal::begin([
        'title' => '<h5 class="modal-title" id="qcModalLabel"></h5>',
        'id' => 'qcModal',
        'size' => 'modal-xl',
        'footer' => false,
    ]); ?>

    <div class="modal-body">
        <input type="hidden" id="qc-modal-id">
        <div class="row mb-3">
            <div class="col-md-2">
                <label>Actual Prod. Date</label>
                <input type="text" class="form-control" id="qc-actual-date" readonly placeholder="Automatic">
            </div>
            <div class="col-md-2">
                <label>Final Result</label>
                <input type="number" class="form-control" id="qc-final-prod" readonly placeholder="Automatic">
            </div>
            <div class="col-md-2">
                <label>Waste (kg)</label>
                <input type="number" class="form-control" id="qc-waste" readonly placeholder="Automatic">
            </div>
            <div class="col-md-2">
                <label>Punch Scrap (kg)</label>
                <input type="number" class="form-control" id="qc-punch" readonly placeholder="Automatic">
            </div>
            <div class="col-md-2">
                <label>Refurbish (kg)</label>
                <input type="number" class="form-control" id="qc-refurbish" readonly placeholder="Automatic">
            </div>
            <div class="col-md-2">
                <label>Remaining Coil (kg)</label>
                <input type="number" class="form-control" id="qc-remaining-coil" readonly placeholder="Automatic">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="qc-final-result" class="form-label">Final Result QC</label>
                <input type="number" class="form-control" id="qc-final-result" placeholder="Enter Final Result">
            </div>

            <div class="col-md-3">
                <label for="qc-reject" class="form-label">Reject QC</label>
                <input type="number" class="form-control" id="qc-reject" placeholder="Reject Data" readonly>
            </div>

            <!-- <div class="col-md-6">
                <label class="form-label">Upload Dokumen QC</label>
                <input type="text" class="form-control" id="qc-document-placeholder" readonly
                    placeholder="Pilih file di form utama (lihat tabel)">
            </div> -->
        </div>
        <h4 style="margin-top:50px;">Sample Result</h4>
        <br>
        <div class="w-100">
            <div class="d-flex flex-nowrap" style="min-width: max-content;">
                <?php for ($set = 1; $set <= 6; $set++): ?>
                    <div class="me-2" style="width: 180px;">
                        <table class="table table-bordered table-sm text-center mb-0">
                            <thead>
                                <tr>
                                    <th colspan="2">Sample QC <?= $set ?></th>
                                </tr>
                                <tr>
                                    <th>Sampel</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($sample = 1; $sample <= 4; $sample++): ?>
                                    <tr>
                                        <td><?= $sample ?>.</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm sample-qc"
                                                data-sample="<?= $sample ?>" data-set="<?= $set ?>" placeholder="Enter Result">
                                        </td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <button type="button" class="btn btn-success" id="saveQcDetail">Save QC</button>
    </div>

    <?php \yii\bootstrap4\Modal::end(); ?>


</div><?php
        $js = <<<JS
$('.btn-production').on('click', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');
    var qtyProduction = parseFloat($(this).data('qty_production')) || 0;


    $('#modal-id').val(id);
    $('#productionModalLabel').text('Form Roll Forming ' + name);
    $('#modal-actual-production-date').val($('#productionrollforming-production_date').val());
    $('#modal-final-result').data('max', qtyProduction);
    $('#modal-final-result').attr('max', qtyProduction);

    $('#modal-final-result').val('');
    $('#modal-waste').val('');
    $('#modal-punch-scrap').val('');
    $('#modal-refurbish').val('');
    $('#modal-remaining-coil').val('');
});

$('#saveProductionDetail').on('click', function () {
    var id = $('#modal-id').val();
    var finalResult = parseFloat($('#modal-final-result').val()) || 0;
    var maxQty = parseFloat($('#modal-final-result').data('max')) || 0;

    if (finalResult > maxQty) {
        alert('Final Result tidak boleh lebih dari Qty Produksi (' + maxQty + ')');
        return;
    }
    $('input[name="actual_production_date[' + id + ']"]').val($('#modal-actual-production-date').val());
    $('input[name="final_result[' + id + ']"]').val($('#modal-final-result').val());
    $('input[name="waste[' + id + ']"]').val($('#modal-waste').val());
    $('input[name="punch_scrap[' + id + ']"]').val($('#modal-punch-scrap').val());
    $('input[name="refurbish[' + id + ']"]').val($('#modal-refurbish').val());
    $('input[name="remaining_coil[' + id + ']"]').val($('#modal-remaining-coil').val());
    $('#productionModal').modal('hide');
});

$('.btn-qc').on('click', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');

    $('#qc-modal-id').val(id);
    $('#qcModalLabel').text('Form QC untuk ' + name);
    $('#qc-actual-date').val($('input[name="actual_production_date[' + id + ']"]').val());
    $('#qc-final-prod').val($('input[name="final_result[' + id + ']"]').val());
    $('#qc-waste').val($('input[name="waste[' + id + ']"]').val());
    $('#qc-punch').val($('input[name="punch_scrap[' + id + ']"]').val());
    $('#qc-refurbish').val($('input[name="refurbish[' + id + ']"]').val());
    $('#qc-remaining-coil').val($('input[name="remaining_coil[' + id + ']"]').val());

    $('#qc-final-result').val('');
    $('#qc-reject').val('');
    $('.sample-qc').val('');

    var fileInput = $('input#hidden-file-' + id)[0];
    var fileName = fileInput && fileInput.files[0] ? fileInput.files[0].name : '';
    $('#qc-document-placeholder').val(fileName);
});

$('#saveQcDetail').on('click', function () {
    var id = $('#qc-modal-id').val();
    var finalResultProduction = parseFloat($('#qc-final-prod').val()) || 0;
    var finalResultQC = parseFloat($('#qc-final-result').val()) || 0;

    if (finalResultQC > finalResultProduction) {
        alert('Final Result QC tidak boleh lebih dari Final Result Produksi (' + finalResultProduction + ')');
        return;
    }

    $('input[name="final_result_qc[' + id + ']"]').val($('#qc-final-result').val());
    $('input[name="reject_qc[' + id + ']"]').val($('#qc-reject').val());

    $('.sample-qc').each(function () {
        var sample = $(this).data('sample');
        var set = $(this).data('set');
        var val = $(this).val();
        var name = 'sample_result_' + sample + '_qc_' + set + '[' + id + ']';
        $('input[name="' + name + '"]').val(val);
    });

    $('#qcModal').modal('hide');
});

$('.btn-upload-file').on('click', function () {
    var id = $(this).data('id');
    $('#hidden-file-' + id).click();
});

$('input[type="file"]').on('change', function () {
    var id = $(this).attr('id').replace('hidden-file-', '');
    var fileName = this.files[0] ? this.files[0].name : '';
    if ($('#qcModal').hasClass('show') && $('#qc-modal-id').val() == id) {
        $('#qc-document-placeholder').val(fileName);
    }
});
$('#qc-final-result').on('input', function () {
    var finalResultProduction = parseFloat($('#qc-final-prod').val()) || 0;
    var finalResultQC = parseFloat($(this).val()) || 0;

    var reject = finalResultProduction - finalResultQC;

    // Jangan biarkan nilai negatif
    if (reject < 0) reject = 0;

    $('#qc-reject').val(reject);
});

JS;

        $this->registerJs($js, \yii\web\View::POS_READY);
        ?>