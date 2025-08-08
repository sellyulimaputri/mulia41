<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\MasterRawMaterial;

/** @var yii\web\View $this */
/** @var app\models\MasterItem $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'item_code')->textInput(['maxlength' => true, 'placeholder' => 'Enter Code']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'item_name')->textInput(['maxlength' => true, 'placeholder' => 'Enter Name']) ?>
        </div>
        <div class="col-md-3">
            <?php
            $rawMaterials = ArrayHelper::map(MasterRawMaterial::find()->where(['item_category' => 1])->all(), 'id', 'item_name');
            echo $form->field($model, 'id_raw_material')->widget(Select2::classname(), [
                'data' => $rawMaterials,
                'options' => ['placeholder' => 'Select Raw Material'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>
    
    <?= $form->field($model, 'notes')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
