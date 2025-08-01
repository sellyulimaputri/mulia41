<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\rollforming\CostProductionRollForming $model */

$this->title = 'Create Cost Production Roll Forming';
$this->params['breadcrumbs'][] = ['label' => 'Cost Production Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cost-production-roll-forming-create">
    <?= $this->render('_form', [
        'model' => $model,
        'details' => $details,
    ]) ?>

</div>