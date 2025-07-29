<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\master\MasterItem $model */

$this->title = 'Update Master Item: ' . $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Master Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->item_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-item-update">

    <div class="card">
        <div class="card-body">
            <?= $this->render('_form', ['model' => $model]) ?>
        </div>
    </div>

</div>