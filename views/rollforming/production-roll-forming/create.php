<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\rollforming\ProductionRollForming $model */

$this->title = 'Create Production Roll Forming';
$this->params['breadcrumbs'][] = ['label' => 'Production Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="production-roll-forming-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>