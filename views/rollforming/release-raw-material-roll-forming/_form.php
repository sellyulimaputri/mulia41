<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\SearchableSelect;

/** @var yii\web\View $this */
/** @var app\models\rollforming\ReleaseRawMaterialRollForming $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="release-raw-material-roll-forming-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'no_release')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-2">
            <label class="control-label">Nomor SO</label>
            <input type="text" class="form-control" value="<?= $model->so ? $model->so->no_so : '' ?>" readonly>
            <?= $form->field($model, 'id_so')->hiddenInput()->label(false) ?>
        </div>

        <div class="col-md-2">
            <label class="control-label">Working Order</label>
            <input type="text" class="form-control" value="<?= $model->worf ? $model->worf->no_planning : '' ?>"
                readonly>
            <?= $form->field($model, 'id_worf')->hiddenInput()->label(false) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'so_date')->textInput(['readonly' => true]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'worf_date')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md-2">
            <label class="control-label">Type Production</label>
            <input type="text" class="form-control" value="<?= $model->getNamaTypeProduction() ?>" readonly>
            <?= $form->field($model, 'type_production')->hiddenInput()->label(false) ?>
        </div>
    </div>

    <?= $form->field($model, 'type_production')->hiddenInput()->label(false) ?>
    <h4>Detail Material</h4>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Raw Material</th>
                <th>Length (mm)</th>
                <th>Reference Max Release</th>
                <th>Qty Produksi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <tbody>
            <?php foreach ($details as $i => $detail): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $detail->soDetail->item->item_name ?? '-' ?></td>
                    <td><?= $detail->soDetail->item->rawMaterial->item_name ?? '-' ?></td>
                    <td><?= $detail->soDetail->length ?? '-' ?></td>
                    <?php
                    $refRelease = ceil((($detail->soDetail->length ?? 0) * ($detail->quantity_production ?? 0)) / ($detail->soDetail->item->rawMaterial->average ?? 1));
                    ?>
                    <td><?= Yii::$app->formatter->asDecimal($refRelease, 0) ?></td>

                    <td><?= $detail->quantity_production ?></td>
                    <td>
                        <div>
                            <?= Html::a('Scan', ['scan', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
                        </div>
                    </td>
                </tr>

                <input type="hidden" name="Detail[<?= $i ?>][id_worf_detail]" value="<?= $detail->id ?>" />
            <?php endforeach; ?>
        </tbody>

        </tbody>
    </table>

    <?= $form->field($model, 'notes')->widget(\dosamigos\ckeditor\CKEditor::class, [
        'options' => ['rows' => 6],
        'preset' => 'standard',
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>