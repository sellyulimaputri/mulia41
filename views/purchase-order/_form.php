<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use unclead\multipleinput\MultipleInput; // Add this line
use app\models\BusinessPartner;

/** @var yii\web\View $this */
/** @var app\models\PurchaseOrder $model */
/** @var yii\widgets\ActiveForm $form */
$this->title = 'Form Finalized Purchase Order';


?>

<div class="purchase-order-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'no_po')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'tanggal')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'id_supplier')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(BusinessPartner::find()->where(['like' , 'type' , 'Vendor'])->all(), 'id', 'nama'),
                'options' => ['placeholder' => 'Select Supplier'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Supplier') ?>
        </div>
    </div>


    <!-- Card for Detail Item -->
    <div class="card mt-4 border border-primary rounded shadow-sm" style="border-width:2px;">
        <div class="card-header bg-primary text-white" style="border-bottom:2px solid #0d6efd;">
            <strong>Detail Item</strong>
        </div>
        <div style="border-bottom:2px solid #dee2e6;"></div>
        <div class="card-body p-4" style="background-color:#f8f9fa;">
            <?= $form->field($model, 'detailItems')->widget(MultipleInput::className(), [
                'columns' => [
                    [
                        'name' => 'id',
                        'type' => 'hiddenInput',
                        'options' => [],
                        'enableError' => false,
                    ],
                    [
                        'name'  => 'item',
                        'title' => 'Item',
                        'type'  => Select2::className(),
                        'options' => [
                            'data' => \yii\helpers\ArrayHelper::map(\app\models\MasterRawMaterial::find()->all(), 'id', 'item_name'),
                            'options' => [
                                'placeholder' => 'Select Item',
                                'onchange' => 'fetchCategoryAndTrigger(this)'
                            ],
                            'pluginOptions' => ['allowClear' => true],
                        ],
                    ],
                    [
                        'name'  => 'category',
                        'title' => 'Category',
                        'type'  => Select2::className(),
                        'options' => [
                            'data' => \yii\helpers\ArrayHelper::map(\app\models\MasterCategory::find()->all(), 'id', 'nama'),
                            'options' => ['placeholder' => 'Select Category', 'onchange' => 'updateCoilFields()' , 'disabled' => true],
                            'pluginOptions' => ['allowClear' => true],
                        ],
                    ],
                    [
                        'name'  => 'type_coil',
                        'title' => 'Type Coil',
                        'type'  => Select2::className(),
                        'options' => [
                            'data' => \yii\helpers\ArrayHelper::map(\app\models\TypeCoil::find()->all(), 'id', 'nama'),
                            'options' => ['placeholder' => 'Select Type Coil'],
                            'pluginOptions' => ['allowClear' => true],
                        ],
                    ],
                    [
                        'name'  => 'thickness',
                        'title' => 'Thickness',
                        'type'  => 'textInput',
                        'options' => ['type'=>'number', 'step'=>'any', 'class'=>'form-control'],
                    ],
                    [
                        'name'  => 'width',
                        'title' => 'Width',
                        'type'  => 'textInput',
                        'options' => ['type'=>'number', 'step'=>'any', 'class'=>'form-control'],
                    ],
                    [
                        'name'  => 'qty',
                        'title' => 'Qty',
                        'type'  => 'textInput',
                        'options' => ['type'=>'number', 'step'=>'any', 'class'=>'form-control', 'min'=>0 , 'onchange' => 'updateTotals()'],
                    ],
                    [
                        'name'  => 'uom',
                        'title' => 'UOM',
                        'type'  => Select2::className(),
                        'options' => [
                            'data' => \yii\helpers\ArrayHelper::map(\app\models\MasterUom::find()->all(), 'id', 'nama'),
                            'options' => ['placeholder' => 'Select UOM'],
                            'pluginOptions' => ['allowClear' => true],
                        ],
                    ],
                    [
                        'name'  => 'price',
                        'title' => 'Price',
                        'type'  => 'textInput',
                        'options' => [
                            'type'=>'number', 'step'=>'any', 'class'=>'form-control',
                            'min'=>0, 'onchange' => 'updateTotals()'
                        ],
                    ],
                    [
                        'name'  => 'total',
                        'title' => 'Total',
                        'type'  => 'textInput',
                        'options' => ['type'=>'number', 'step'=>'any', 'class'=>'form-control', 'readonly' => 'readonly'],
                    ],
                ]
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <!-- End Card for Detail Item -->

    <?php ActiveForm::end(); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateTotals() {
        document.querySelectorAll('.multiple-input-list__item').forEach(function(row) {
            var qty = row.querySelector('input[name*="[qty]"]');
            var price = row.querySelector('input[name*="[price]"]');
            var total = row.querySelector('input[name*="[total]"]');
            if (qty && price && total) {
                var qtyVal = parseFloat(qty.value) || 0;
                var priceVal = parseFloat(price.value) || 0;
                total.value = (qtyVal * priceVal).toFixed(2);
            }
        });
    }
    
    // Inline onchange handler for item select
    function fetchCategoryAndTrigger(selectElem) {
        var $row = $(selectElem).closest('.multiple-input-list__item');
        var itemId = $(selectElem).val();
        var $categorySelect = $row.find('select[name*="[category]"]');
        if (itemId) {
            $.ajax({
                url: '/dev/web/purchase-order/get-category-by-item',
                type: 'GET',
                data: {id: itemId},
                success: function(data) {
                    if (data && data.category_id) {
                        $categorySelect.val(data.category_id).trigger('change');
                    } else {
                        $categorySelect.val('').trigger('change');
                    }
                },
                error: function() {
                    $categorySelect.val('').trigger('change');
                }
            });
        } else {
            $categorySelect.val('').trigger('change');
        }
    }

    function updateCoilFields() {
        console.log('updateCoilFields function called');
        
        // For each row in MultipleInput
        $('.multiple-input-list__item').each(function() {
            console.log('Processing row:', $(this));
            
            var categorySelect = $(this).find('select[name*="[category]"]');
            var typeCoil = $(this).find('select[name*="[type_coil]"]');
            var thickness = $(this).find('input[name*="[thickness]"]');
            var width = $(this).find('input[name*="[width]"]');
            
            console.log('Found elements:', {
                categorySelect: categorySelect.length,
                typeCoil: typeCoil.length,
                thickness: thickness.length,
                width: width.length
            });
            
            if (categorySelect.length && categorySelect.val()) {
                var selectedCategory = categorySelect.val();
                console.log('Selected category:', selectedCategory);
                //alert('Category selected: ' + selectedCategory);
                
                if (selectedCategory == 1) {
                    console.log('Enabling fields');
                    typeCoil.prop('disabled', false);
                    thickness.prop('disabled', false);
                    width.prop('disabled', false);
                } else {
                    console.log('Disabling fields');
                    typeCoil.prop('disabled', true);
                    thickness.prop('disabled', true);
                    width.prop('disabled', true);
                }
            } else {
                console.log('No category selected, disabling fields');
                typeCoil.prop('disabled', true);
                thickness.prop('disabled', true);
                width.prop('disabled', true);
            }
        });
    }






    // Initial calculation and field state on page load
    $(function() {
        console.log('Page loaded, initializing...');
        //alert('Page loaded!');
        updateTotals();
        updateCoilFields();
    });
</script>

</div>
