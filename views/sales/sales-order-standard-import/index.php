<?php

use app\models\master\BusinessPartner;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use app\widgets\SearchableSelect;
use app\models\master\MasterCustomer;
use app\models\sales\SalesOrderStandard;

/** @var yii\web\View $this */
/** @var app\models\sales\SalesOrderStandardSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Sales Order Standards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-order-standard-index">
    <p>
        <?= Html::a('Create Sales Order Standard', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'no_so',

            [
                'attribute' => 'tanggal',
                'format' => ['date', 'php:Y-m-d'],
                'filter' => Html::input('date', 'SalesOrderStandardSearch[tanggal]', $searchModel->tanggal, ['class' => 'form-control']),
            ],
            [
                'attribute' => 'id_customer',
                'value' => 'customer.nama',
                'filter' => SearchableSelect::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_customer',
                    'items' => BusinessPartner::getDropdownList(),
                    'options' => ['class' => 'form-control'],
                    'prompt' => 'All Customer',
                ]),
            ],
            [
                'attribute' => 'deliver_date',
                'format' => ['date', 'php:Y-m-d'],
                'filter' => Html::input('date', 'SalesOrderStandardSearch[deliver_date]', $searchModel->deliver_date, ['class' => 'form-control']),
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SalesOrderStandard $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>