<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use app\widgets\SearchableSelect;
use app\models\rollforming\WorkingOrderRollForming;
use app\models\rollforming\ReleaseRawMaterialRollForming;

/** @var yii\web\View $this */
/** @var app\models\rollforming\ReleaseRawMaterialRollFormingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Release Raw Material Roll Formings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="release-raw-material-roll-forming-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'no_release',
            [
                'attribute' => 'id_so',
                'value' => 'so.no_so',
                'filter' => SearchableSelect::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_so',
                    'items' => \app\models\sales\SalesOrderStandard::getDropdownList(),
                    'options' => ['class' => 'form-control'],
                    'prompt' => 'All Sales Order',
                ]),
            ],
            [
                'attribute' => 'id_worf',
                'value' => 'worf.no_planning',
                'filter' => SearchableSelect::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_worf',
                    'items' => WorkingOrderRollForming::getDropdownList(),
                    'options' => ['class' => 'form-control'],
                    'prompt' => 'All Working Order',
                ]),
            ],
            [
                'attribute' => 'so_date',
                'format' => ['date', 'php:Y-m-d'],
                'filter' => Html::input('date', 'ReleaseRawMaterialRollFormingSearch[so_date]', $searchModel->so_date, ['class' => 'form-control']),
            ],
            [
                'attribute' => 'worf_date',
                'format' => ['date', 'php:Y-m-d'],
                'filter' => Html::input('date', 'ReleaseRawMaterialRollFormingSearch[worf_date]', $searchModel->worf_date, ['class' => 'form-control']),
            ],
            [
                'attribute' => 'type_production',
                'value' => 'namaTypeProduction',
                'filter' => Html::dropDownList(
                    'WorkingOrderRollFormingSearch[type_production]',
                    $searchModel->type_production,
                    [
                        '' => 'All Type Production', // Option to clear the filter
                        '1' => 'Roll Forming',       // Option for Roll Production
                        '2' => 'Powder Coating',        // Option for Powder Coating
                    ],
                    ['class' => 'form-control']
                ),
            ],
            //'notes:ntext',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, ReleaseRawMaterialRollForming $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{view} {delete}',
            ],

        ],
    ]); ?>


</div>