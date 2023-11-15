<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Units;
use app\models\DeviceTypes;
use froala\froalaeditor\FroalaEditorWidget;
?>

<div class="connections-form">

    <?php
    $form = ActiveForm::begin();
    $device_types = DeviceTypes::find()->all();
    $items_devices = ArrayHelper::map($device_types, 'id', 'name');
    $units = Units::unitsDropdownList();
    $items_units = ArrayHelper::map($units, 'id', 'name');
    ?>
    <?= $form->field($model, 'hostname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->widget(FroalaEditorWidget::class, [
        'name' => 'content',
        'options' => [
            // html attributes
            'id' => 'content'
        ],
        'clientOptions' => [
            'toolbarInline' => false,
            'key' => 'trial',
            'theme' => 'royal', //optional: dark, red, gray, royal
            'language' => 'ru', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
            'key' => "1C%kZV[IX)_SL}UJHAEFZMUJOYGYQE[\\ZJ]RAe(+%$==",
            'attribution' => false,
            'toolbarButtons'   => ['bold', 'italic', 'underline',
            'strikeThrough', 'subscript', 'superscript',
            'fontFamily', 'fontSize', 'color', 'inlineStyle', 'paragraphStyle',
            'paragraphFormat', 'align', 'clearFormatting', 'html'],
        ]
    ])
    ?>

    <?= $form->field($model, 'ipaddr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_type_id')->dropDownList($items_devices); ?>

    <?= $form->field($model, 'unit_id')->dropDownList($items_units,  ['encodeSpaces' => true]); ?>

    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>
