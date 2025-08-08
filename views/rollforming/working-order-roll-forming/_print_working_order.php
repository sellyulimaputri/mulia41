<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\rollforming\WorkingOrderRollForming $model */
?>

<h2 style="text-align:center;">Working Order Roll Forming</h2>
<hr>

<table width="100%" style="margin-bottom: 20px;">
    <tr>
        <td><strong>Planning No.:</strong></td>
        <td><?= Html::encode($model->no_planning) ?></td>
        <td><strong>Production Date:</strong></td>
        <td><?= Yii::$app->formatter->asDate($model->production_date) ?></td>
    </tr>
    <tr>
        <td><strong>Sales Order No.:</strong></td>
        <td><?= Html::encode($model->so->no_so ?? '-') ?></td>
        <td><strong>Sales Order Date:</strong></td>
        <td><?= Yii::$app->formatter->asDate($model->so_date) ?></td>
    </tr>
    <tr>
        <td><strong>Production Type:</strong></td>
        <td><?= $model->namaTypeProduction ?></td>
        <td><strong>Status:</strong></td>
        <td><?= $model->getStatus() ?></td>
    </tr>
</table>

<h4>Production Details</h4>
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; margin-top: 10px;">
    <thead>
        <tr>
            <th style="text-align:center;">#</th>
            <th>Item</th>
            <th>Length</th>
            <th>Production Qty</th>
            <th>Unit</th>
            <th>Raw Material</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($model->workingOrderRollFormingDetails as $detail): ?>
            <?php
            $soDetail = $detail->soDetail;
            $item = $soDetail->item ?? null;
            $rawMaterial = $item->rawMaterial ?? null;
            ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= $item->item_name ?? '-' ?></td>
                <td><?= Yii::$app->formatter->asDecimal($soDetail->length ?? 0, 2) ?> mm</td>
                <td align="right"><?= $detail->quantity_production ?></td>
                <td><?= $soDetail->uom->name ?? '-' ?></td>
                <td><?= $rawMaterial->item_name ?? '-' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h4>Raw Material Details</h4>
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th style="text-align:center;">#</th>
            <th>Product Name</th>
            <th>Raw Material</th>
            <th>Length (mm)</th>
            <th>Production Qty</th>
            <th>Reference Max Release</th>
            <th>GR No.</th>
            <th>Supplier Code</th>
            <th>Initial Weight</th>
            <th>Locator</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach ($model->releaseRawMaterialRollFormingDetails as $detail):
            foreach ($detail->qrs as $qr):
                $scan = $qr->scanResult;
        ?>
                <tr>
                    <td align="center"><?= $no++ ?></td>
                    <td><?= $detail->worfDetail->soDetail->item->item_name ?? '-' ?></td>
                    <td><?= $detail->rawMaterial->item_name ?? '-' ?></td>
                    <td align="right"><?= $detail->worfDetail->soDetail->length ?? '-' ?></td>
                    <td align="right"><?= $detail->worfDetail->quantity_production ?? '-' ?></td>
                    <td align="right"><?= $detail->reference_max_release ?></td>
                    <td><?= $scan->header->no_good_receipt ?? '-' ?></td>
                    <td><?= $scan->supplier_code ?? '-' ?></td>
                    <td><?= $scan->berat_awal ?? '-' ?></td>
                    <td><?= $scan->locater ?: '-' ?></td>
                </tr>
        <?php
            endforeach;
        endforeach;
        ?>
    </tbody>
</table>

<?php if (!empty($model->notes)): ?>
    <div style="margin-top: 20px;">
        <strong>Notes:</strong><br>
        <?= \yii\helpers\HtmlPurifier::process($model->notes) ?>
    </div>
<?php endif; ?>


<!-- Signature Block -->
<br><br><br>
<table width="100%" style="text-align:center; margin-top: 50px;">
    <tr>
        <td><strong>Prepared By</strong></td>
        <td><strong>Checked By</strong></td>
        <td><strong>Approved By</strong></td>
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