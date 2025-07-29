<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use app\widgets\SearchableSelect;
use app\models\sales\SalesOrderStandard;
use app\models\rollforming\WorkingOrderRollForming;

/** @var yii\web\View $this */
/** @var app\models\rollforming\WorkingOrderRollFormingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Production Roll Formings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="production-roll-forming-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'no_planning',
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
                'attribute' => 'so_date',
                'format' => ['date', 'php:Y-m-d'],
                'filter' => Html::input('date', 'WorkingOrderRollFormingSearch[so_date]', $searchModel->so_date, ['class' => 'form-control']),
            ],
            [
                'attribute' => 'production_date',
                'format' => ['date', 'php:Y-m-d'],
                'filter' => Html::input('date', 'WorkingOrderRollFormingSearch[production_date]', $searchModel->production_date, ['class' => 'form-control']),
            ],
            [
                'attribute' => 'type_production',
                'value' => 'namaTypeProduction',
                'filter' => Html::dropDownList(
                    'WorkingOrderRollFormingSearch[type_production]',
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
                'value' => function ($model) {
                    return $model->getStatus();
                },
                'filter' => Html::dropDownList(
                    'WorkingOrderRollFormingSearch[status]',
                    $searchModel->status,
                    [
                        '' => 'All Status',
                        0 => 'Belum Direlease',
                        1 => 'Direlease',
                        2 => 'Diproduksi',
                    ],
                    ['class' => 'form-control']
                ),
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{production}',
                'buttons' => [
                    'production' => function ($url, $model, $key) {
                        if ($model->status == 1) {
                            return Html::a(
                                '<i class="fa fa-share-square"></i>',
                                ['create', 'id_worf' => $model->id],
                                [
                                    'title' => 'Production',
                                    'data-pjax' => '0'
                                ]
                            );
                        }
                        return null;
                    },
                ],
                'urlCreator' => function ($action, WorkingOrderRollForming $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
</div>