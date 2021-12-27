<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Units;
use app\models\DeviceTypes;

/* @var $this yii\web\View */
/* @var $model app\models\Connections */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="connections-form">

    <?php
    $form = ActiveForm::begin();
    $device_types = DeviceTypes::find()->all();
    $items_devices = ArrayHelper::map($device_types, 'id', 'name');
    $units = Units::unitsDropdownList();
    $items_units = ArrayHelper::map($units, 'id', 'name');
    ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ipaddr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_type_id')->dropDownList($items_devices); ?>

    <?= $form->field($model, 'unit_id')->dropDownList($items_units,  ['encodeSpaces' => true]); ?>




    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
