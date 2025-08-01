<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\SearchableSelect;
use app\models\sales\SalesOrderStandard;

/** @var yii\web\View $this */
/** @var app\models\rollforming\WorkingOrderRollForming $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="working-order-roll-forming-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'no_planning')->textInput(['maxlength' => true, 'placeholder' => 'Enter No Planning']) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'id_so')->widget(SearchableSelect::class, [
                'items' => \app\models\sales\SalesOrderStandard::getDropdownList(),
                'options' => [
                    'class' => 'form-control',
                    'onchange' => '
                        var soId = this.value;
                        var noPlanning = document.getElementById("workingorderrollforming-no_planning").value;
                        var prodDate = document.getElementById("workingorderrollforming-production_date").value;

                        var url = "' . \yii\helpers\Url::to(['rollforming/working-order-roll-forming/create']) . '"
                            + "?id_so=" + soId
                            + "&no_planning=" + encodeURIComponent(noPlanning)
                            + "&production_date=" + encodeURIComponent(prodDate);

                        window.location.href = url;
                    '
                ],
                'prompt' => 'Pilih Sales Order',
            ]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'so_date')->textInput(['readonly' => true, 'placeholder' => 'Automatic']) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'production_date')->input('date') ?>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Type Production</label>
                <input type="text" class="form-control" value="<?= $model->getNamaTypeProduction() ?>" readonly>
            </div>
            <?= $form->field($model, 'type_production')->hiddenInput()->label(false) ?>
        </div>
    </div>

    <?php if (!empty($soDetails)): ?>
        <h4>Detail Sales Order</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Length</th>
                    <th>Deskripsi</th>
                    <th>Production</th>
                    <th>Color (code)</th>
                    <th>Qty</th>
                    <th>Remaining Qty</th>
                    <th>Qty Production</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($soDetails as $detail): ?>
                    <tr>
                        <td><?= Html::encode($detail->item->item_name ?? '-') ?></td>
                        <td><?= $detail->length ?> mm</td>
                        <td><?= $detail->description ?></td>
                        <td><?= $detail->namaTypeProduksi ?></td>
                        <td><?= $detail->rawMaterial->item_code ?? '-' ?></td>
                        <td><?= $detail->qty ?></td>
                        <td><?= $detail->remaining_qty ?? '-' ?></td>
                        <td>
                            <input type="number" class="form-control" name="qty_production[<?= $detail->id ?>]"
                                <?= (isset($detail->remaining_qty) && $detail->remaining_qty <= 0) ? 'disabled' : '' ?>
                                placeholder="Enter Qty Production" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?= $form->field($model, 'notes')->widget(\dosamigos\ckeditor\CKEditor::class, [
        'options' => ['rows' => 6],
        'preset' => 'standard',
    ]) ?>

    <div class=" form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>