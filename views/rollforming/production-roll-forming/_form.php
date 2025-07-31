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
            <?= $form->field($model, 'no_production')->textInput(['maxlength' => true]) ?>
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
    <table class="table table-bordered table-condensed">
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
                            data-name="<?= $detail->soDetail->item->item_name ?? '-' ?>">
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
                <input type="number" class="form-control" name="waste[<?= $detail->id ?>]" style="display:none;">
                <input type="number" class="form-control" name="punch_scrap[<?= $detail->id ?>]" style="display:none;">
                <input type="number" class="form-control" name="refurbish[<?= $detail->id ?>]" style="display:none;">
                <input type="number" class="form-control" name="remaining_coil[<?= $detail->id ?>]" style="display:none;">

                <input type="number" class="form-control" name="final_result_qc[<?= $detail->id ?>]" style="display:none;">
                <input type="number" class="form-control" name="reject_qc[<?= $detail->id ?>]" style="display:none;">
                <!-- Hidden file input untuk QC, agar tersubmit -->
                <input type="file" name="document_qc[<?= $detail->id ?>]" class="document-upload-hidden"
                    id="hidden-file-<?= $detail->id ?>" style="display:none;">
                <input type="number" class="form-control" name="sample_result_1_qc[<?= $detail->id ?>]"
                    style="display:none;">
                <input type="number" class="form-control" name="sample_result_2_qc[<?= $detail->id ?>]"
                    style="display:none;">
                <input type="number" class="form-control" name="sample_result_3_qc[<?= $detail->id ?>]"
                    style="display:none;">
                <input type="number" class="form-control" name="sample_result_4_qc[<?= $detail->id ?>]"
                    style="display:none;">


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
                <input type="date" class="form-control" id="modal-actual-production-date">
            </div>

            <div class="col-md-2">
                <label for="modal-final-result" class="form-label">Final Result</label>
                <input type="number" class="form-control" id="modal-final-result">
            </div>

            <div class="col-md-2">
                <label for="modal-waste" class="form-label">Waste</label>
                <input type="number" class="form-control" id="modal-waste">
            </div>

            <div class="col-md-2">
                <label for="modal-punch-scrap" class="form-label">Punch Scrap</label>
                <input type="number" class="form-control" id="modal-punch-scrap">
            </div>

            <div class="col-md-2">
                <label for="modal-refurbish" class="form-label">Refurbish</label>
                <input type="number" class="form-control" id="modal-refurbish">
            </div>

            <div class="col-md-2">
                <label for="modal-remaining-coil" class="form-label">Remaining Coil</label>
                <input type="number" class="form-control" id="modal-remaining-coil">
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
                <label for="qc-final-result" class="form-label">Final Result QC</label>
                <input type="number" class="form-control" id="qc-final-result">
            </div>

            <div class="col-md-3">
                <label for="qc-reject" class="form-label">Reject QC</label>
                <input type="number" class="form-control" id="qc-reject">
            </div>

            <div class="col-md-6">
                <label class="form-label">Upload Dokumen QC</label>
                <input type="text" class="form-control" id="qc-document-placeholder" readonly
                    placeholder="Pilih file di form utama (lihat tabel)">
            </div>
        </div>

        <div class="row mb-3">
            <?php for ($s = 1; $s <= 4; $s++): ?>
                <div class="col-md-3">
                    <label for="qc-sample-<?= $s ?>" class="form-label">Sample <?= $s ?> QC</label>
                    <input type="number" class="form-control" id="qc-sample-<?= $s ?>">
                </div>
            <?php endfor; ?>
        </div>

        <button type="button" class="btn btn-success" id="saveQcDetail">Save QC</button>
    </div>

    <?php \yii\bootstrap4\Modal::end(); ?>


</div>
<?php
$js = <<<JS
$('.btn-production').on('click', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');

    // Set ID ke hidden field
    $('#modal-id').val(id);

    // Set title modal
    $('#productionModalLabel').text('Form Roll Forming ' + name);

    // Kosongkan input modal
    $('#modal-actual-production-date').val('');
    $('#modal-final-result').val('');
    $('#modal-waste').val('');
    $('#modal-punch-scrap').val('');
    $('#modal-refurbish').val('');
    $('#modal-remaining-coil').val('');
});


$('#saveProductionDetail').on('click', function () {
    var id = $('#modal-id').val();

    // Salin nilai dari modal ke input tersembunyi
    $('input[name="actual_production_date[' + id + ']"]').val($('#modal-actual-production-date').val());
    $('input[name="final_result[' + id + ']"]').val($('#modal-final-result').val());
    $('input[name="waste[' + id + ']"]').val($('#modal-waste').val());
    $('input[name="punch_scrap[' + id + ']"]').val($('#modal-punch-scrap').val());
    $('input[name="refurbish[' + id + ']"]').val($('#modal-refurbish').val());
    $('input[name="remaining_coil[' + id + ']"]').val($('#modal-remaining-coil').val());

    // Tutup modal (Bootstrap 4)
    $('#productionModal').modal('hide');
});

// QC Modal Open
$('.btn-qc').on('click', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');

    $('#qc-modal-id').val(id);
    $('#qcModalLabel').text('Form QC untuk ' + name);

    // Ambil data dari input hidden yang diisi oleh modal production
    $('#qc-actual-date').val($('input[name="actual_production_date[' + id + ']"]').val());
    $('#qc-final-prod').val($('input[name="final_result[' + id + ']"]').val());
    $('#qc-waste').val($('input[name="waste[' + id + ']"]').val());
    $('#qc-punch').val($('input[name="punch_scrap[' + id + ']"]').val());
    $('#qc-refurbish').val($('input[name="refurbish[' + id + ']"]').val());
    $('#qc-remaining-coil').val($('input[name="remaining_coil[' + id + ']"]').val());

    // Kosongkan input QC
    $('#qc-final-result').val('');
    $('#qc-reject').val('');
    $('#qc-document').val('');
    for (var i = 1; i <= 4; i++) {
        $('#qc-sample-' + i).val('');
    }
    
    var fileInput = $('input#hidden-file-' + id)[0];
    var fileName = fileInput && fileInput.files[0] ? fileInput.files[0].name : '';
    $('#qc-document-placeholder').val(fileName);
});


// QC Modal Save
$('#saveQcDetail').on('click', function () {
    var id = $('#qc-modal-id').val();

    $('input[name="final_result_qc[' + id + ']"]').val($('#qc-final-result').val());
    $('input[name="reject_qc[' + id + ']"]').val($('#qc-reject').val());
    $('input[name="sample_result_1_qc[' + id + ']"]').val($('#qc-sample-1').val());
    $('input[name="sample_result_2_qc[' + id + ']"]').val($('#qc-sample-2').val());
    $('input[name="sample_result_3_qc[' + id + ']"]').val($('#qc-sample-3').val());
    $('input[name="sample_result_4_qc[' + id + ']"]').val($('#qc-sample-4').val());

    // File input tidak dapat di-set dengan JavaScript, tetap disimpan manual oleh user

    $('#qcModal').modal('hide');
});

// Tombol upload file per baris
$('.btn-upload-file').on('click', function () {
    var id = $(this).data('id');
    $('#hidden-file-' + id).click();
});
// Tampilkan nama file di placeholder saat file dipilih
$('input[type="file"]').on('change', function () {
    var id = $(this).attr('id').replace('hidden-file-', '');
    var fileName = this.files[0] ? this.files[0].name : '';

    if ($('#qcModal').hasClass('show') && $('#qc-modal-id').val() == id) {
        $('#qc-document-placeholder').val(fileName);
    }
});

JS;

$this->registerJs($js, \yii\web\View::POS_READY);

?>