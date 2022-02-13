<?php
/* @var $this yii\web\View */
?>
<?php

use yii\widgets\ActiveForm;
use app\models\Units;
use app\models\DeviceTypes;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = "Импорт из Radmin"
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
    <h3>Краткое руководство по импорту</h3>
    <p>Импорт из Radmin возможен пока только через дополнительный импорт посредством обработки 1С.</p>
    <p>Обработку можно скачать по <a href="https://github.com/kuzyara/Import-export-radmin-book-1C" target="_blank">ссылке</a> 
        или по <a href="/import-1C/RadminImportExport.epf" target="_blank">этой ссылке</a>, если первая недоступна</p>
    <p>Затем необходимо добавить эту обработку в любую конфигурацию 1С.</p>
    <p>Также если нет желания портить какую-либо конфигурацию 1С, можете скачать готовую конфигурацию 1С только с этой обработкой по 
        <a href="/import-1C/RadminImportExport.cf" target="_blank">ссылке</a></p>
    <p>Обработка будет доступна в меню Сервис.</p>    
    <p>Путь до файла адресной книги Radmin нужно вставлять, кнопка обзор может не работать.</p>
    <p>После добавления обработки или конфигурации 1С запускаем 1С и выполняем следующие действия:</p>
    <ol>
    <li>Переходим в меню Сервис -> Импорт/экспорт адресной книги Radmin</li>
        <li>В первой строке "Путь к файлу" указываем путь к файлу адресной книги Radmin с расширением .rpb</li>
    <li>Во второй строке "Путь к файлу" указываем путь к файлу, куда мы хотим сохранить импортирумую книгу с расширением .csv</li>
    <li>Жмём кнопку "Сохранить"</li>
    <li>Полученный файл прикрепляем к этой странице</li></ol>
    <p>Также данная обработка позволяет осуществлять обратный экспор данных из CSV файла в файл адресной книги Radmin.</p>
</div>
