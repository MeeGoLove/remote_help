<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\ConnectionTypes;

/* @var $this yii\web\View */
/* @var $model app\models\DeviceTypes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="device-types-form">

    <?php $form = ActiveForm::begin(); 
    
    $connections_types = ConnectionTypes::find()->all();
    $connections_types_items = ArrayHelper::map($connections_types,'id','name');
    $params = [
        'prompt' => 'Выберите тип подключения'
    ];
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
  
    <?= $form->field($model, 'default_connection_type_id')->dropDownList($connections_types_items, $params) ?>
    
    <?= $form->field($model, 'optional_connection_type_id')->dropDownList($connections_types_items, $params) ?>
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
