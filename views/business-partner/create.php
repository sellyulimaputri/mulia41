<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BusinessPartner $model */

$this->title = 'Create Business Partner';
$this->params['breadcrumbs'][] = ['label' => 'Business Partners', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-partner-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
