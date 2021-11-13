<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Units;
use app\models\DeviceTypes;
use kvelaro\TreeHelper\TreeHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Connections */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="connections-form">

    <?php $form = ActiveForm::begin();
    $device_types = DeviceTypes::find()->all();
    $items_devices = ArrayHelper::map($device_types,'id','name');
    $units = Units::find()->all();
    $items_units = ArrayHelper::map($units,'id','name');
    //var_dump($items_units);
    TreeHelper::makeTree($units, 'id', 'parent_id'); //build tree

    var_dump($tree = TreeHelper::getTree($units, '&nbsp;'));
    echo Html::activeDropDownList($model,'unit_id',$items_units, ['encodeSpaces'=>true]);
    $tree = TreeHelper::getTree(); //get tree





?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ipaddr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'device_type_id')->dropDownList($items_devices); ?>






    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
