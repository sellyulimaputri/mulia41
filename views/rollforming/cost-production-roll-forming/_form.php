<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/** @var yii\web\View $this */
/** @var app\models\rollforming\CostProductionRollForming $model */
/** @var yii\widgets\ActiveForm $form */
\wbraganca\dynamicform\DynamicFormAsset::register($this);

?>

<div class="cost-production-roll-forming-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-2">
            <label class="control-label">No Production</label>
            <input type="text" class="form-control"
                value="<?= $model->production ? $model->production->no_production : '' ?>" readonly>
            <?= $form->field($model, 'id_production')->hiddenInput()->label(false) ?>
        </div>

        <div class="col-md-2">
            <label class="control-label">No Working Order</label>
            <input type="text" class="form-control" value="<?= $model->worf ? $model->worf->no_planning : '' ?>"
                readonly>
            <?= $form->field($model, 'id_worf')->hiddenInput()->label(false) ?>
        </div>
        <div class="col-md-2">
            <label class="control-label">No Sales Order</label>
            <input type="text" class="form-control" value="<?= $model->so ? $model->so->no_so : '' ?>" readonly>
            <?= $form->field($model, 'id_so')->hiddenInput()->label(false) ?>
        </div>


        <div class="col-md-2">
            <?= $form->field($model, 'so_date')->textInput(['readonly' => true]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'production_date')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md-2">
            <label class="control-label">Type Production</label>
            <input type="text" class="form-control" value="<?= $model->getNamaTypeProduction() ?>" readonly>
            <?= $form->field($model, 'type_production')->hiddenInput()->label(false) ?>
        </div>
    </div>
    <div class="panel panel-default">
        <fieldset class="border p-3 rounded mb-3">
            <legend class="w-auto px-2">Detail Biaya Produksi</legend>
            <div class="panel-body">
                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper',
                    'widgetBody' => '.container-items',
                    'widgetItem' => '.item',
                    'limit' => 20,
                    'min' => 1,
                    'insertButton' => '.add-item',
                    'deleteButton' => '.remove-item',
                    'model' => $details[0],
                    'formId' => $form->id,
                    'formFields' => ['description', 'nominal', 'notes'],
                ]); ?>

                <div class="container-items">
                    <?php foreach ($details as $i => $detail): ?>
                        <div class="item panel panel-info mb-2">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <?= $form->field($detail, "[$i]description")->textInput(['maxlength' => true, 'placeholder' => 'Enter Description'])->label('Deskripsi') ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $form->field($detail, "[$i]nominal")->textInput([
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'class' => 'form-control text-right',
                                            'placeholder' => 'Enter Nominal',
                                        ])->label('Nominal') ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $form->field($detail, "[$i]notes")->textInput(['maxlength' => true, 'placeholder' => 'Enter Catatan'])->label('Catatan') ?>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <div class="form-group">
                                            <button type="button" class="remove-item btn btn-danger btn-sm">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


                <div class="form-group">
                    <button type="button" class="add-item btn btn-success btn-sm"><i class="fa fa-plus"></i>
                        Tambah</button>
                </div>

                <?php DynamicFormWidget::end(); ?>
            </div>
        </fieldset>
    </div>

    <?= $form->field($model, 'notes')->widget(\dosamigos\ckeditor\CKEditor::class, [
        'options' => ['rows' => 6],
        'preset' => 'standard',
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>