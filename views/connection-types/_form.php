<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConnectionTypes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="connection-types-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'protocol_link')->textInput(['maxlength' => true]) ?>




        <?= $form->field($model, 'icon')->fileInput(); ?>
        <?php
        if (!empty($model->icon)) {
            $img = Yii::getAlias('@webroot') . '/icons-remote/source/' . $model->icon;
            if (is_file($img)) {
                $url = Yii::getAlias('@web') . '/icons-remote/source/' . $model->icon;
                echo 'Уже загружена ', Html::a('иконка', $url, ['target' => '_blank']), ', если хотите изменить иконку, выберите нужный файл сверху';
            }
        }
        ?>
<p></p>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
