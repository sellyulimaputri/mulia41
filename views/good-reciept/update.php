<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\GoodReciept $model */

$this->title = 'Update Good Reciept: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Good Reciepts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="good-reciept-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
