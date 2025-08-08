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

$this->title = 'Working Order Roll Formings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="working-order-roll-forming-index">
    <p>
        <?= Html::a('Create Working Order Roll Forming', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

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
                        0 => 'Not Released Yet',
                        1 => 'Released',
                        2 => 'Produced',
                        3 => 'Done',
                        4 => 'Partial QC Approve',
                    ],
                    ['class' => 'form-control']
                ),
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete} {create-partial-qc}',
                'visibleButtons' => [
                    'update' => fn($model) => $model->status == 0,
                    'delete' => fn($model) => $model->status == 0,
                    'create-partial-qc' => fn($model) => $model->status == 4 && $model->productionRollForming !== null,
                ],
                'buttons' => [
                    'create-partial-qc' => function ($url, $model, $key) {
                        $production = $model->productionRollForming;
                        if ($production === null) {
                            return ''; // jika tidak ada production terkait
                        }
                        return Html::a(
                            '<span class="fas fa-check-circle"></span>',
                            ['rollforming/working-order-roll-forming/create-partial-qc-approve', 'id' => $production->id],
                            [
                                'title' => 'Create Partial QC Approve',
                                'aria-label' => 'Create Partial QC Approve',
                                'data-pjax' => '0',
                                'data-confirm' => 'Apakah Anda yakin ingin membuat Partial QC Approve?'
                            ]
                        );
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'create-partial-qc') {
                        $production = $model->productionRollForming;
                        if ($production !== null) {
                            return Url::to(['rollforming/working-order-roll-forming/create-partial-qc-approve', 'id' => $production->id]);
                        }
                    }
                    return Url::to([$action, 'id' => $model->id]);
                },
            ],

        ],
    ]); ?>


</div>