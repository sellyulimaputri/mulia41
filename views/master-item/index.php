<?php

use app\models\MasterItem;
use app\models\MasterCategory;
use app\models\MasterRawMaterial;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterItemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_name',
            'item_code',
            [
                'attribute' => 'id_raw_material',
                'label' => 'Raw Material',
                'value' => function ($model) {
                    $model2 = MasterRawMaterial::findOne($model->id_raw_material);
                    return $model2 ? $model2->item_name : null;
                }
            ],
            'notes',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
