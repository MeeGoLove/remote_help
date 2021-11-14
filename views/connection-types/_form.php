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


    <fieldset>
        <legend>Загрузить изображение</legend>
        <?= $form->field($model, 'icon')->fileInput(); ?>
        <?php
        if (!empty($model->icon)) {
            $img = Yii::getAlias('@webroot') . '/icons-remote/source/' . $model->icon;
            if (is_file($img)) {
                $url = Yii::getAlias('@web') . '/icons-remote/source/' . $model->icon;
                echo 'Уже загружено ', Html::a('изображение', $url, ['target' => '_blank']);
            }
        }
        ?>
    </fieldset>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
