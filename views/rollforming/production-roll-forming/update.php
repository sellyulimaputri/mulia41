<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\rollforming\ProductionRollForming $model */

$this->title = 'Update Production Roll Forming: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Production Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="production-roll-forming-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
