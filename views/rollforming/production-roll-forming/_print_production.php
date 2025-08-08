<?php
use yii\helpers\Html;

/** @var $model app\models\rollforming\ProductionRollForming */
?>

<h2 style="text-align: center;">Production Roll Forming</h2>
<hr>

<table width="100%" border="0" cellpadding="5">
    <tr>
        <td><b>No. Production</b></td>
        <td><?= $model->no_production ?></td>
    </tr>
    <tr>
        <td><b>Sales Order</b></td>
        <td><?= $model->so->no_so ?? '-' ?></td>
    </tr>
    <tr>
        <td><b>Production Date</b></td>
        <td><?= Yii::$app->formatter->asDate($model->production_date) ?></td>
    </tr>
    <tr>
        <td><b>Type</b></td>
        <td><?= $model->namaTypeProduction ?></td>
    </tr>
    <tr>
        <td><b>Notes</b></td>
        <td><?= $model->notes ?></td>
    </tr>
</table>

<br>
<h3>Production Detail</h3>
<table border="1" width="100%" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Item</th>
            <th>Production Date</th>
            <th>Final Result</th>
            <th>Waste</th>
            <th>Punch Scrap</th>
            <th>Refurbish</th>
            <th>Remaining Coil</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model->productionRollFormingDetails as $i => $detail): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= $detail->worfDetail->soDetail->item->item_name ?? '-' ?></td>
            <td><?= Yii::$app->formatter->asDate($detail->actual_production_date) ?></td>
            <td><?= $detail->final_result ?></td>
            <td><?= $detail->waste ?></td>
            <td><?= $detail->punch_scrap ?></td>
            <td><?= $detail->refurbish ?></td>
            <td><?= $detail->remaining_coil ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<!-- Tanda Tangan -->
<br><br><br>
<table width="100%" style="text-align:center; margin-top: 50px;">
    <tr>
        <td><strong>Dibuat Oleh</strong></td>
        <td><strong>Diperiksa Oleh</strong></td>
        <td><strong>Disetujui Oleh</strong></td>
    </tr>
    <tr>
        <td height="80px"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>(_____________________)</td>
        <td>(_____________________)</td>
        <td>(_____________________)</td>
    </tr>
</table>