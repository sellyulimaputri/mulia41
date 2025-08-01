<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use app\widgets\SearchableSelect;
use app\models\sales\SalesOrderStandard;
use app\models\rollforming\ProductionRollForming;
use app\models\rollforming\WorkingOrderRollForming;
use app\models\rollforming\CostProductionRollForming;

/** @var yii\web\View $this */
/** @var app\models\rollforming\CostProductionRollFormingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Cost Production Roll Formings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cost-production-roll-forming-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'no_production',
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
                'filter' => Html::input('date', 'ProductionRollFormingSearch[so_date]', $searchModel->so_date, ['class' => 'form-control']),
            ],
            [
                'attribute' => 'production_date',
                'format' => ['date', 'php:Y-m-d'],
                'filter' => Html::input('date', 'ProductionRollFormingSearch[production_date]', $searchModel->production_date, ['class' => 'form-control']),
            ],
            [
                'attribute' => 'type_production',
                'value' => 'namaTypeProduction',
                'filter' => Html::dropDownList(
                    'ProductionRollFormingSearch[type_production]',
                    $searchModel->type_production,
                    [
                        '' => 'All Type Production',
                        '1' => 'Roll Forming',
                        '2' => 'Powder Coating',
                    ],
                    ['class' => 'form-control']
                ),
            ],
            [
                'attribute' => 'status',
                'value' => 'namaStatus',
                'filter' => Html::dropDownList(
                    'ProductionRollFormingSearch[status]',
                    $searchModel->status,
                    [
                        '' => 'All Status',
                        '0' => '-',
                        '1' => 'Done',
                    ],
                    ['class' => 'form-control']
                ),
            ],

            [
                'class' => ActionColumn::className(),
                'template' => '{view} {cost}',
                'buttons' => [
                    'cost' => function ($url, $model, $key) {
                        if ($model->status == 0) {
                            return Html::a(
                                '<i class="fa fa-share-square"></i>',
                                ['create', 'id_production' => $model->id],
                                [
                                    'title' => 'Cost Production',
                                    'data-pjax' => '0'
                                ]
                            );
                        }
                        return null;
                    },
                    'view' => function ($url, $model, $key) {
                            return Html::a(
                                '<i class="fa fa-eye"></i>',
                                ['rollforming/production-roll-forming/view', 'id' => $model->id],
                                [
                                    'title' => 'View',
                                    'data-pjax' => '0'
                                ]
                            );
                    },
                ],
                'urlCreator' => function ($action, ProductionRollForming $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
            //'notes:ntext',
        ],
    ]); ?>


</div>