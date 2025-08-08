<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BusinessPartner $model */

$this->title = 'Update Business Partner: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Business Partners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="business-partner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
