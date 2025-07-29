<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\sales\SalesOrderStandardSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sales-order-standard-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'no_so') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'id_customer') ?>

    <?= $form->field($model, 'deliver_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
