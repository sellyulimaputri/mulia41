<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterItem $model */

// $this->title = 'Create Master Item';
$this->params["breadcrumbs"][] = [
    "label" => "Create Master Items",
    "url" => ["index"],
];
$this->params["breadcrumbs"][] = $this->title;
?>
<div class="master-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render("_form", [
        "model" => $model,
    ]) ?>

</div>
