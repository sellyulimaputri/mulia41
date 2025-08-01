<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\rollforming\CostProductionRollForming $model */

$this->title = 'Update Cost Production Roll Forming: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cost Production Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cost-production-roll-forming-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
