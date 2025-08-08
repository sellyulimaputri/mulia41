<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterRawMaterialSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-raw-material-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'item_category') ?>

    <?= $form->field($model, 'item_name') ?>

    <?= $form->field($model, 'uom') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'type_coil') ?>

    <?php // echo $form->field($model, 'notes') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
