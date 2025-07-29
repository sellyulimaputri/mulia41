<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\rollforming\WorkingOrderRollFormingSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="working-order-roll-forming-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'no_planning') ?>

    <?= $form->field($model, 'id_so') ?>

    <?= $form->field($model, 'so_date') ?>

    <?= $form->field($model, 'production_date') ?>

    <?php // echo $form->field($model, 'type_production') ?>

    <?php // echo $form->field($model, 'notes') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
