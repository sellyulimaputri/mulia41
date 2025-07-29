<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\rollforming\ReleaseRawMaterialRollForming $model */

$this->title = 'Create Release Raw Material Roll Forming';
$this->params['breadcrumbs'][] = ['label' => 'Release Raw Material Roll Formings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="release-raw-material-roll-forming-create">

    <?= $this->render('_form', [
        'model' => $model,
        'details' => $details,
    ]) ?>

</div>