<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\rollforming\WorkingOrderRollForming $model */

$this->title = 'Update Working Order Roll Forming: ' . $model->no_planning;
$this->params['breadcrumbs'][] = ['label' => 'Working Order Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_planning, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="working-order-roll-forming-update">

    <?= $this->render('_form', [
        'model' => $model,
        'soDetails' => $soDetails,
    ]) ?>

</div>