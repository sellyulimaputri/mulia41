<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterRawMaterial $model */

$this->title = 'Create Master Raw Material';
$this->params['breadcrumbs'][] = ['label' => 'Master Raw Materials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-raw-material-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
