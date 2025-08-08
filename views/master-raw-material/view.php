<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\MasterRawMaterial $model */

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Master Raw Materials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-raw-material-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Category',
                'attribute' => 'item_category',
                'value' => function($model) {
                    if (is_object($model->itemCategory)) {
                        return $model->itemCategory->nama ?? '(not set)';
                    }
                    return '(not set)';
                },
            ],
            'item_name',
            [
                'label' => 'UOM',
                'attribute' => 'uom',
                'value' => function($model) {
                    // If $model->uom is an object (relation), get nama
                    if (is_object($model->uom)) {
                        return $model->uom->nama ?? '(not set)';
                    }
                    // If $model->uom is an id, fetch the related model
                    $uomModel = \app\models\MasterUom::findOne($model->uom);
                    return $uomModel ? $uomModel->nama : '(not set)';
                },
            ],
            'weight',
            'type_coil',
            'notes',
        ],
    ]) ?>

</div>
