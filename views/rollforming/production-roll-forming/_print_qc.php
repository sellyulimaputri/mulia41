<?php

use yii\helpers\Html;

/** @var $model app\models\rollforming\ProductionRollForming */
?>

<h2 style="text-align: center;">Quality Control - Roll Forming</h2>
<hr>

<table width="100%" border="0" cellpadding="5">
    <tr>
        <td><b>No. Production</b></td>
        <td><?= $model->no_production ?></td>
    </tr>
    <tr>
        <td><b>Production Date</b></td>
        <td><?= Yii::$app->formatter->asDate($model->production_date) ?></td>
    </tr>
</table>

<?php foreach ($model->productionRollFormingDetails as $i => $detail): ?>
<hr style="margin: 20px 0;">
<h4>Item <?= $i + 1 ?>: <?= $detail->worfDetail->soDetail->item->item_name ?? '-' ?></h4>

<table border="1" width="100%" cellpadding="5" cellspacing="0">
    <tr>
        <th>Final Result QC</th>
        <th>Reject QC</th>
        <th>QC Document</th>
    </tr>
    <tr>
        <td><?= $detail->final_result_qc ?></td>
        <td><?= $detail->reject_qc ?></td>
        <td>
            <?php if ($detail->document_qc): ?>
            <a href="<?= Yii::getAlias('@web') . '/uploads/' . $detail->document_qc ?>" target="_blank">Download</a>
            <?php else: ?>
            Tidak ada
            <?php endif; ?>
        </td>
    </tr>
</table>

<br>
<h5>Sample Result</h5>
<table border="1" cellpadding="4" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>QC</th>
            <th>Sample 1</th>
            <th>Sample 2</th>
            <th>Sample 3</th>
            <th>Sample 4</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($qc = 1; $qc <= 6; $qc++): ?>
        <tr>
            <td>QC <?= $qc ?></td>
            <?php for ($s = 1; $s <= 4; $s++): ?>
            <?php
                        $attr = "sample_result_{$s}_qc_{$qc}";
                        echo "<td>" . ($detail->$attr ?? '-') . "</td>";
                        ?>
            <?php endfor; ?>
        </tr>
        <?php endfor; ?>
    </tbody>
</table>
<?php endforeach; ?>

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