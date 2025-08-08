<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\TypeCoil $model */

$this->title = 'Create Type Coil';
$this->params['breadcrumbs'][] = ['label' => 'Type Coils', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-coil-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
