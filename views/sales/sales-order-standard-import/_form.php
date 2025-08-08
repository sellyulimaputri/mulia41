<?php

use app\models\master\BusinessPartner;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\widgets\SearchableSelect;
use app\models\master\MasterCustomer;

/** @var yii\web\View $this */
/** @var app\models\sales\SalesOrderStandard $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="sales-order-standard-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'no_so')->textInput(['maxlength' => true,'placeholder' => 'Enter No Sales Order']) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'tanggal')->input('date') ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'id_customer')->widget(SearchableSelect::class, [
                'items' => BusinessPartner::getDropdownList(),
                'options' => ['class' => 'form-control'],
                'prompt' => 'Pilih Customer',
            ]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'deliver_date')->input('date') ?>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Upload Excel</label>
                <div class="custom-file">
                    <!-- Hidden file input, styled custom button for file upload -->
                    <input type="file" name="excelFile" class="custom-file-input" id="excelFile" accept=".xls,.xlsx"
                        required>
                    <label class="custom-file-label" for="excelFile">Pilih File</label>
                </div>
                <small class="form-text text-muted">Please upload an Excel file (.xls, .xlsx).</small>
            </div>
        </div>
    </div>

    <div class="form-group mt-2">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$css = <<<CSS
    .custom-file-label::after {
        content: "Browse";
    }
    .custom-file-input:lang(en)~.custom-file-label {
        border-radius: 0.375rem;
        background-color: #ffffff;
        border: 1px solid #ced4da;
    }
    .custom-file-input:focus~.custom-file-label {
        border-color: #80bdff;
        outline: 0;
    }
CSS;
$this->registerCss($css);
?>

<?php
$js = <<<JS
    // Update the label text with the selected file name
    $('#excelFile').on('change', function (e) {
        var fileName = e.target.files[0].name;
        $(this).next('.custom-file-label').html(fileName);
    });
JS;
$this->registerJs($js);
?>