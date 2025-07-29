<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\rollforming\ReleaseRawMaterialRollForming $model */

$this->title = 'Update Release Raw Material Roll Forming: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Release Raw Material Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="release-raw-material-roll-forming-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>