<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\master\MasterItem $model */

$this->title = 'Create Master Item';
$this->params['breadcrumbs'][] = ['label' => 'Master Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-item-create">

    <div class="card">
        <div class="card-body">
            <?= $this->render('_form', ['model' => $model]) ?>
        </div>
    </div>

</div>