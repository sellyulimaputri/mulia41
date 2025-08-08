<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\MasterCategory;  
use app\models\MasterUom;
use app\models\TypeCoil;

/** @var yii\web\View $this */
/** @var app\models\MasterRawMaterial $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-raw-material-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?=
                $form->field($model, 'item_category')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(MasterCategory::find()->all(), 'id', 'nama'),
                    'options' => ['placeholder' => 'Select a category...', 'id' => 'item_category_select' , 'onchange' => 'toggleWeightRow()'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'item_code')->textInput() ?>
        </div>

        <div class="col-md-3">  
            <?= $form->field($model, 'item_name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-3">
            <?= 
                $form->field($model, 'uom')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(MasterUom::find()->all(), 'id', 'nama'),
                    'options' => ['placeholder' => 'Select a UOM...', 'id' => 'uom_select'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) 
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3" id="weight-row" style="display:none;">
            <?= $form->field($model, 'weight')->input('number', ['step' => 'any']) ?>
        </div>
        <div class="col-md-3" id ="type_coil-row" style="display:none;">
            <?=  
                $form->field($model, 'type_coil')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(TypeCoil::find()->all(), 'id', 'nama'),
                    'options' => ['placeholder' => 'Select a Type Coil...', 'id' => 'type_coil_select'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) 
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'notes')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>

<?php
$coilCategoryId = null;
$categories = \app\models\MasterCategory::find()->all();
foreach ($categories as $cat) {
    if (strtolower($cat->nama) === 'coil') {
        $coilCategoryId = $cat->id;
        break;
    }
}
?>
<script>
    var coilCategoryId = '<?= $coilCategoryId ?>';
    var select = document.getElementById('item_category_select');
    var weightRow = document.getElementById('weight-row');
    var typeCoilRow = document.getElementById('type_coil-row');
    function toggleWeightRow() {
        if (select.value == coilCategoryId) {
            weightRow.style.display = '';
            typeCoilRow.style.display = '';
        } else {
            weightRow.style.display = 'none';
            typeCoilRow.style.display = 'none';
        }
        //alert("tes");
    }
    select.addEventListener('change', toggleWeightRow);
    // For Select2, also listen to the change event via jQuery
    if (window.jQuery) {
        $('#item_category_select').on('change', toggleWeightRow);
    }
    // Initial state
    toggleWeightRow();
</script>

</div>
