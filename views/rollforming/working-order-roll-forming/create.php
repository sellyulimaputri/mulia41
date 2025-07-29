<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\rollforming\WorkingOrderRollForming $model */

$this->title = 'Create Working Order Roll Forming';
$this->params['breadcrumbs'][] = ['label' => 'Working Order Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="working-order-roll-forming-create">

    <?= $this->render('_form', [
        'model' => $model,
        'soDetails' => $soDetails,
    ]) ?>

</div>