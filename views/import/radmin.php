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
    <?= $form->field($model, 'rootUnitId')->dropDownList($items_units, ['encodeSpaces' => true]); ?>
    <?= $form->field($model, 'clearDir')->checkbox(); ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end() ?>
    <h3>Руководство по импорту</h3>
    <p>Импорт из Radmin возможен пока только через дополнительный импорт посредством обработки 1С.</p>
    <p>Обработку можно скачать по <a href="https://github.com/kuzyara/Import-export-radmin-book-1C" target="_blank">ссылке</a></p>
    <p>Затем необходимо добавить эту обработку в конфигурацию 1С.</p>
    <p>Обработка будет доступна в меню Сервис.</p>
    <p>Путь до файла нужно вставлять, кнопка обзор не работает</p>
</div>
