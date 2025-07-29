<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\sales\SalesOrderStandard $model */

$this->title = 'Create Sales Order Standard';
$this->params['breadcrumbs'][] = ['label' => 'Sales Order Standards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-order-standard-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>