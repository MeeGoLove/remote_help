<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\icons\Icon;
use app\models\Connections;
use lo\widgets\modal\ModalAjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подключения';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="connections-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Создать подключение', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'ipaddr',
            'comment',
            //'unit_id',
            [
                'attribute' => 'device_type_id',
                'value' => function ($model, $key, $index, $widget) {
                    return $model->deviceType->name;
                },
                'format' => 'raw',
            ],
            /*[
                'attribute' => 'unit_id',
                'value' => function ($model, $key, $index, $widget) {
                    return $model->unit->name;
                },
                'format' => 'raw',
            ],*/
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}{add}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return ModalAjax::widget([
                                    'id' => 'updateUnit' . $key,
                                    'header' => 'Изменить подразделение',
                                    'toggleButton' => [
                                        'label' => '',
                                        'class' => 'glyphicon glyphicon-pencil'
                                    ],
                                    'url' => '/connections/update?id=' . $key, // Ajax view with form to load
                                    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                                    'size' => ModalAjax::SIZE_LARGE,
                                    'options' => ['class' => 'header-primary'],
                                    'autoClose' => true,
                                    'pjaxContainer' => '#grid-company-pjax',
                                    'events' => [
                                        ModalAjax::EVENT_MODAL_SUBMIT => new \yii\web\JsExpression("function(event, data, status, xhr, selector) {window.location.reload();}")
                                    ]
                                ]);
                            },
                        ]],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>
</div>
