<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterRawMaterial $model */

$this->title = 'Update Master Raw Material: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Master Raw Materials', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-raw-material-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
