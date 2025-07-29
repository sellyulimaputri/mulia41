<?php

use app\models\master\MasterItem;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\master\MasterItemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Master Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-item-index">
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
            'id_raw_material',
            'notes',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, MasterItem $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>