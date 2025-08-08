<?php

use app\models\MasterRawMaterial;
use app\models\MasterCategory;
use app\models\MasterUom;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterRawMaterialSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Raw Materials';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-raw-material-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Raw Material', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'item_category',
                'value' => function ($model){
                    $model2 = MasterCategory::findOne($model->item_category);
                    return $model2->nama;
                }
            ],
            'item_name',
            'item_code',
            [
                'attribute' => 'uom',
                'value' => function ($model){
                    $model2 = MasterUom::findOne($model->uom);
                    return $model2->nama;
                }
            ],
            //'weight',
            //'type_coil',
            //'notes',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterRawMaterial $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>