<?php
/* @var $this yii\web\View */
?>
<?php

use yii\widgets\ActiveForm;
use app\models\Units;
use app\models\DeviceTypes;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>

<div class="radmin-import-form">
<?php
$form = ActiveForm::begin();
$device_types = DeviceTypes::find()->all();
$items_devices = ArrayHelper::map($device_types, 'id', 'name');
$units = Units::unitsDropdownList();
$items_units = ArrayHelper::map($units, 'id', 'name');
?>

<?= $form->field($model, 'importFile')->fileInput() ?>
<?= $form->field($model, 'deviceTypeId')->dropDownList($items_devices); ?>
<?= $form->field($model, 'rootUnitId')->dropDownList($items_units); ?>
<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end() ?>
</div>