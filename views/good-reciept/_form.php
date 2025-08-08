<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\BusinessPartner;
use app\models\PurchaseOrder;

/** @var yii\web\View $this */
/** @var app\models\GoodReciept $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="good-reciept-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'no_good_receipt')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'id_supplier')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(BusinessPartner::find()->where(['type' => 'Vendor'])->all(), 'id', 'nama'),
                'options' => ['placeholder' => 'Select Supplier', 'id' => 'supplier-select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Supplier') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'no_po')->widget(Select2::classname(), [
                'data' => [],
                'options' => ['placeholder' => 'Select PO', 'id' => 'po-select'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Purchase Order') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'po_date')->textInput(['readonly' => true, 'id' => 'po-date']) ?>
        </div>
        <div class="col-md-2">
        <?= $form->field($model, 'no_do')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'receive_date')->textInput(['type' => 'date']) ?>
        </div>
    </div>

    <!-- Card for PO Details -->
    <div class="card mt-4 border border-primary rounded shadow-sm" id="po-details-card" style="display: none; border-width:2px;">
        <div class="card-header bg-primary text-white" style="border-bottom:2px solid #0d6efd;">
            <div class="d-flex justify-content-between align-items-center">
                <strong>PO Details</strong>
                
            </div>
        </div>
        <div style="border-bottom:2px solid #dee2e6;"></div>
        <div class="card-body p-4" style="background-color:#f8f9fa;">
            <div id="po-details-content">
                <!-- PO details will be loaded here -->
            </div>
        </div>
    </div>
    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <!-- Coil Data Section (hidden by default) -->
    <div id="coil-data-section" style="display:none; margin-top:30px;">
        <div class="card border border-info rounded shadow-sm">
            <div class="card-header bg-info text-white">
                <strong>Coil Data Entry</strong>
                <button type="button" class="btn btn-sm btn-danger float-end" id="hide-coil-table">Hide</button>
            </div>
            <div class="card-body">
                <?php
                use unclead\multipleinput\MultipleInput;
                echo MultipleInput::widget([
                    'id' => 'coil-multiple-input',
                    'name' => 'coil_data',
                    'value' => [], // You can prefill with existing data if needed
                    'columns' => [
                        [
                            'name'  => 'id_material',
                            'title' => 'ID Material',
                            'options' => [
                                'readonly' => true
                            ]
                        ],
                        [
                            'name'  => 'supplier_code',
                            'title' => 'Supplier Code',
                        ],
                        [
                            'name'  => 'berat_awal',
                            'title' => 'Berat Awal',
                            'type'  => 'textInput',
                            'options' => ['class' => 'form-control'],
                        ],
                        [
                            'name'  => 'locater',
                            'title' => 'Locater',
                        ],
                    ],
                    'addButtonOptions' => ['class' => 'btn btn-success'],
                    'removeButtonOptions' => ['class' => 'btn btn-danger'],
                ]);
                ?>
                <button type="button" class="btn btn-success" id="save-coil-data">Save & Print</button>
            </div>
        </div>
    </div>
    <!-- View Data Section (hidden by default) -->
    <div id="view-data-section" style="display:none; margin-top:30px;">
        <div class="card border border-secondary rounded shadow-sm">
            <div class="card-header bg-secondary text-white">
                <strong>View Coil/Item Data</strong>
                <button type="button" class="btn btn-sm btn-danger float-end" id="hide-view-table">Hide</button>
            </div>
            <div class="card-body" id="view-data-body">
                <!-- Details will be injected here -->
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>


<?php
$getPoBySupplierUrl = \yii\helpers\Url::base() . '/good-reciept/get-po-by-supplier';
$getPoDetailsUrl = \yii\helpers\Url::base() . '/good-reciept/get-po-details';
$getPoItemDetailsUrl = \yii\helpers\Url::base() . '/good-reciept/get-po-item-details';
$saveAndPrintUrl = \yii\helpers\Url::base() . '/good-reciept/save-and-print';
$checkPembatasanBerat = \yii\helpers\Url::base() . '/good-reciept/check-pembatasan-berat';
// Add custom CSS for modal
$css = <<<CSS
.modal-backdrop {
    z-index: 1040;
}
.modal {
    z-index: 1050;
}
CSS;
$this->registerCss($css);

$script = <<<JS
$(document).ready(function() {
    var currentPoDetails = [];
    var grDetailCounter = 0;
    var idDetail = 0;
    // Handle supplier selection
    $('#supplier-select').on('change', function() {
        var supplierId = $(this).val();
        var poSelect = $('#po-select');
        var poDateField = $('#po-date');
        var poDetailsCard = $('#po-details-card');
        var grDetailsCard = $('#gr-details-card');
        
        // Clear PO selection and date
        poSelect.empty().append('<option value="">Select PO</option>');
        poDateField.val('');
        poDetailsCard.hide();
        grDetailsCard.hide();
        
        if (supplierId) {
            // Fetch PO data for selected supplier
            $.ajax({
                url: '$getPoBySupplierUrl',
                type: 'GET',
                data: {id_supplier: supplierId},
                dataType: 'json',
                success: function(data) {
                    poSelect.empty().append('<option value="">Select PO</option>');
                    $.each(data, function(index, item) {
                        poSelect.append('<option value="' + item.id + '" data-tanggal="' + item.tanggal + '">' + item.no_po + '</option>');
                    });
                },
                error: function() {
                    alert('Error loading PO data');
                }
            });
        }
    });
    
    // Handle PO selection
    $('#po-select').on('change', function() {
        var poId = $(this).val();
        var selectedOption = $(this).find('option:selected');
        var poDateField = $('#po-date');
        var poDetailsCard = $('#po-details-card');
        var poDetailsContent = $('#po-details-content');
        var grDetailsCard = $('#gr-details-card');
        
        if (poId) {
            // Set PO date
            poDateField.val(selectedOption.data('tanggal'));
            
            // Fetch PO details
            $.ajax({
                url: '$getPoDetailsUrl',
                type: 'GET',
                data: {id_header: poId},
                dataType: 'json',
                success: function(data) {
                    currentPoDetails = data;
                    var html = '<div class="table-responsive"><table class="table table-striped table-bordered">';
                    html += '<thead class="table-dark"><tr>';
                    html += '<th>Item</th><th>Type Coil</th><th>Thickness</th><th>Width</th><th>Qty</th><th>UOM</th><th>Price</th><th>Outstanding</th><th>Action</th>';
                    html += '</tr></thead><tbody>';
                    
                    $.each(data, function(index, item) {
                        html += '<tr>';
                        html += '<td>' + item.item_name + '</td>';
                        html += '<td>' + item.type_coil + '</td>';
                        html += '<td>' + (item.thickness || '-') + '</td>';
                        html += '<td>' + (item.width || '-') + '</td>';
                        html += '<td>' + item.qty + '</td>';
                        html += '<td>' + item.uom + '</td>';
                        html += '<td>' + item.harga + '</td>';
                        html += '<td>' + item.outstanding + '</td>';
                        html += '<td>';
                        html += '<button type="button" class="btn btn-sm btn-primary me-1" onclick="addData(' + item.id + ')">Add Data</button>';
                        html += '<button type="button" class="btn btn-sm btn-info" onclick="viewData(' + item.id + ')">View Data</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                    
                    html += '</tbody></table></div>';
                    poDetailsContent.html(html);
                    poDetailsCard.show();
                    grDetailsCard.show();
                    
                    // Show action buttons
                    $('#add-data-btn').show();
                    $('#view-data-btn').show();
                },
                error: function() {
                    alert('Error loading PO details');
                }
            });
        } else {
            poDateField.val('');
            poDetailsCard.hide();
            grDetailsCard.hide();
            $('#add-data-btn').hide();
            $('#view-data-btn').hide();
        }
    });
    
    // Add Data button handler
    $('#add-data-btn').on('click', function() {
        if (currentPoDetails.length > 0) {
            // Add all PO details as GR details
            $.each(currentPoDetails, function(index, item) {
                addGrDetailRow(item);
            });
            alert('All PO details have been added to Good Receipt details!');
        } else {
            alert('No PO details available to add.');
        }
    });

    // View Data button handler
    $('#view-data-btn').on('click', function() {
        var grDetails = [];
        $('#gr-details-tbody tr').each(function() {
            var row = $(this);
            grDetails.push({
                item: row.find('td:eq(0)').text(),
                type_coil: row.find('td:eq(1)').text(),
                thickness: row.find('td:eq(2)').text(),
                width: row.find('td:eq(3)').text(),
                qty_received: row.find('td:eq(4)').text(),
                uom: row.find('td:eq(5)').text(),
                remarks: row.find('td:eq(6)').text()
            });
        });
        
        var modalBody = $('#viewDataModalBody');
        var html = '<div class="table-responsive"><table class="table table-striped table-bordered">';
        html += '<thead class="table-info"><tr>';
        html += '<th>Item</th><th>Type Coil</th><th>Thickness</th><th>Width</th><th>Qty Received</th><th>UOM</th><th>Remarks</th>';
        html += '</tr></thead><tbody>';
        
        $.each(grDetails, function(index, item) {
            html += '<tr>';
            html += '<td>' + item.item + '</td>';
            html += '<td>' + item.type_coil + '</td>';
            html += '<td>' + item.thickness + '</td>';
            html += '<td>' + item.width + '</td>';
            html += '<td>' + item.qty_received + '</td>';
            html += '<td>' + item.uom + '</td>';
            html += '<td>' + item.remarks + '</td>';
            html += '</tr>';
        });
        
        html += '</tbody></table></div>';
        modalBody.html(html);
        $('#viewDataModal').modal('show');
    });
    
    // Add GR Detail button handler
    $('#add-gr-detail-btn').on('click', function() {
        if (currentPoDetails.length > 0) {
            // Show a simple prompt to select which item to add
            var itemOptions = '';
            $.each(currentPoDetails, function(index, item) {
                itemOptions += '<option value="' + index + '">' + item.item_name + '</option>';
            });
            
            var selectedIndex = prompt('Select item to add (0-' + (currentPoDetails.length - 1) + '):', '0');
            if (selectedIndex !== null && selectedIndex >= 0 && selectedIndex < currentPoDetails.length) {
                addGrDetailRow(currentPoDetails[selectedIndex]);
            }
        } else {
            alert('No PO details available. Please select a PO first.');
        }
    });
    
    function addGrDetailRow(poDetail) {
        grDetailCounter++;
        var row = '<tr id="gr-row-' + grDetailCounter + '" data-po-detail-id="' + poDetail.id + '">';
        row += '<td>' + poDetail.item_name + '</td>';
        row += '<td>' + poDetail.type_coil + '</td>';
        row += '<td>' + (poDetail.thickness || '-') + '</td>';
        row += '<td>' + (poDetail.width || '-') + '</td>';
        row += '<td><input type="number" class="form-control form-control-sm" name="gr_qty[]" value="' + poDetail.qty + '" step="0.01"></td>';
        row += '<td>' + poDetail.uom + '</td>';
        row += '<td><input type="text" class="form-control form-control-sm" name="gr_remarks[]" placeholder="Enter remarks"></td>';
        row += '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeGrRow(' + grDetailCounter + ')"><i class="fa fa-trash"></i></button></td>';
        row += '</tr>';
        $('#gr-details-tbody').append(row);
    }
    
    // Global function to remove GR detail row
    window.removeGrRow = function(rowId) {
        $('#gr-row-' + rowId).remove();
    };
    // Global function to add data for specific item
    window.addData = function(itemId) {
        // Show coil data section
        fetch('$checkPembatasanBerat'+'?idDetail=' + itemId)
        .then(response => response.json())
        .then(data => {
            // Process the data received from the server
            if(data.status == 'success') {
                $('#coil-data-section').show();
                IdDetail = itemId; // Always use array for consistency
                window._coilItemCode = data.item_code;
                window._coilItemCount = parseInt(data.item_count);
                // Auto-generate ID Material for each coil row (unique per row)
                var rows = $('#coil-multiple-input .multiple-input-list__item');
                rows.each(function(idx) {
                    var seq = (window._coilItemCount + idx).toString().padStart(5, '0');
                    var idMaterial = window._coilItemCode + seq;
                    $(this).find('input[name$="[id_material]"]').val(idMaterial);
                });
                // Listen for add row button click to auto-generate ID Material for new row
                $('#coil-multiple-input').off('click.generateIdMaterial').on('click.generateIdMaterial', '.multiple-input-list__btn.btn-success', function() {
                    setTimeout(function() {
                        var rows = $('#coil-multiple-input .multiple-input-list__item');
                        rows.each(function(idx) {
                            var seq = (window._coilItemCount + idx).toString().padStart(5, '0');
                            var idMaterial = window._coilItemCode + seq;
                            $(this).find('input[name$="[id_material]"]').val(idMaterial);
                        });
                    }, 100);
                });
            }else
            {
                alert('Berat Sudah Melebihi Batas');   
            }
        });
    };
    $('#hide-coil-table').on('click', function() {
        $('#coil-data-section').hide();
    });
    $('#save-coil-data').on('click', function() {
        // Gather coil data from MultipleInput
        var coilData = [];
        $('#coil-multiple-input .multiple-input-list__item').each(function(idx) {
            var row = {};
            row.id_material = $(this).find('input[name$="[id_material]"]').val();
            row.supplier_code = $(this).find('input[name$="[supplier_code]"]').val();
            row.berat_awal = $(this).find('input[name$="[berat_awal]"]').val();
            row.locater = $(this).find('input[name$="[locater]"]').val();
            coilData.push(row);
        });
        // Use IdDetail array for po_detail_id
        // if (!Array.isArray(IdDetail) || IdDetail.length === 0 || IdDetail[0] === undefined || IdDetail[0] === null || IdDetail[0] === '') {
        //     alert('PoDetail ID is missing!');
        //     return;
        // }
        var postData = {
            po_detail_id: IdDetail
        };
        // Convert coilData array to separate arrays for each field
        postData['id_material'] = coilData.map(function(row){ return row.id_material; });
        postData['supplier_code'] = coilData.map(function(row){ return row.supplier_code; });
        postData['berat_awal'] = coilData.map(function(row){ return row.berat_awal; });
        postData['locater'] = coilData.map(function(row){ return row.locater; });
        $.ajax({
            url: '$saveAndPrintUrl',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#coil-data-section').hide();
                    // Clear coil data table
                    $('#coil-multiple-input .multiple-input-list__item').remove();
                    // Open QR code for each inserted row
                    if (response.saved_ids && Array.isArray(response.saved_ids)) {
                        response.saved_ids.forEach(function(id) {
                            window.open('/mulia/web/good-reciept/generate-qr-row?id=' + id, '_blank');
                        });
                    }
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Error saving coil data!');
            }
        });
    });
    // Global function to view data for specific item
    window.viewData = function(itemId) {
        // Fetch po_item_detail data for this item
        $.ajax({
            url: '$getPoItemDetailsUrl',
            type: 'GET',
            data: {id_header: itemId},
            dataType: 'json',
            success: function(response) {
                if (response.success && Array.isArray(response.data)) {
                    var html = '<div class="table-responsive"><table class="table table-striped table-bordered">';
                    html += '<thead class="table-info"><tr>';
                    html += '<th>No</th><th>ID Material</th><th>Supplier Code</th><th>Berat Awal</th><th>Locater</th><th>Action</th>';
                    html += '</tr></thead><tbody>';
                    response.data.forEach(function(row, idx) {
                        html += '<tr>';
                        html += '<td>' + (idx + 1) + '</td>';
                        html += '<td>' + (row.id_material || '-') + '</td>';
                        html += '<td>' + (row.supplier_code || '-') + '</td>';
                        html += '<td>' + (row.berat_awal || '-') + '</td>';
                        html += '<td>' + (row.locater || '-') + '</td>';
                        html += '<td>';
                        html += '<button type="button" class="btn btn-sm btn-success" onclick="printQrRow(' + (row.id || '-') + ')">Print QR</button>';
                        html += '</td>';
                        html += '</tr>';
                    });
                    html += '</tbody></table></div>';
                    $('#view-data-body').html(html);
                    $('#view-data-section').show();
                } else {
                    $('#view-data-body').html('<div class="alert alert-warning">No data found for this item.</div>');
                    $('#view-data-section').show();
                }
            },
            error: function() {
                $('#view-data-body').html('<div class="alert alert-danger">Error loading data.</div>');
                $('#view-data-section').show();
            }
        });
    };
    $('#hide-view-table').on('click', function() {
        $('#view-data-section').hide();
    });

    window.printQrRow = function(id) {
        window.open('/mulia/web/good-reciept/generate-qr-row?id=' + id, '_blank');
    };
    
});
JS;

$this->registerJs($script);
?>
