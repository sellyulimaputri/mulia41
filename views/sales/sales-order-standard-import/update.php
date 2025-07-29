<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\sales\SalesOrderStandard $model */

$this->title = 'Update Sales Order Standard: ' . $model->no_so;
$this->params['breadcrumbs'][] = ['label' => 'Sales Order Standards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_so, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sales-order-standard-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>