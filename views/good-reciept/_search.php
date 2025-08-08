<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\GoodRecieptSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="good-reciept-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'no_good_receipt') ?>

    <?= $form->field($model, 'id_supplier') ?>

    <?= $form->field($model, 'no_po') ?>

    <?= $form->field($model, 'po_date') ?>

    <?php // echo $form->field($model, 'no_do') ?>

    <?php // echo $form->field($model, 'receive_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
