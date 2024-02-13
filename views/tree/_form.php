<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Units;

/* @var $this yii\web\View */
/* @var $model app\models\Units */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tree-form">

    <?php
    $units = Units::unitsDropdownList();
    $items_units = ArrayHelper::map($units, 'id', 'name');
    $form = ActiveForm::begin();
    ?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= !Units::isRoot($model->id)?$form->field($model, 'parent_id')->dropDownList($items_units, ['encodeSpaces' => true]):"" ?>


    <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>
