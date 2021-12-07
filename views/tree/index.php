<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
use app\components\connectionswidget\ConnectionsGridWidget;
use lo\widgets\modal\ModalAjax;
/* * *ext** */
use leandrogehlen\treegrid\TreeGrid;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Адресная книга';
$this->params['breadcrumbs'][] = ['label' => 'Подключения', 'url' => ['']];
$this->params['breadcrumbs'][] = 'Хлебные крошки ещё не готовы!!!';
?>
<div class="tree-index">

    <?php //echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?php
        if ($dataProvider->count == 0)
            echo Html::a('Создать корневой элемент', ['add'], ['class' => 'btn btn-success']);
        //$dataProvider = new ActiveDataProvider(['query' => \app\models\Units::find()->orderBy(['name' => SORT_ASC])]);
        ?>
    </p>



    <div class="row">      
        <?php Pjax::begin(); ?>  

        <div class="col-md-5 noscrollbar" 
             style="overflow-y: scroll; height: 700px; scrollbar-width: none;">
            <!-- стиль, чтобы убрать полосу прокрутки-->
            <style>
                .noscrollbar::-webkit-scrollbar {
                    width: 0px;
                }
            </style>

            <!-- блок с tree -->

            <?php
            ?><?=
            TreeGrid::widget([
                'dataProvider' => $dataProvider,
                'keyColumnName' => 'id',
                'showOnEmpty' => FALSE,
                'parentColumnName' => 'parent_id',
                //способ подсвечивать текущее выбранное подразделение
                'rowOptions' => function ($model, $key) {
                    $request = Yii::$app->request;
                    $unit_id = $request->get('unit_id');
                    if ($key == $unit_id) {
                        return ['class' => 'info'];
                    }
                },
                'columns' =>
                [
                    //'name',
                    [
                        //'attribute' => 'name',
                        'label' => 'Иерархия',
                        'value' => function (\app\models\Units $data) {
                            return Html::a(Html::encode($data->name), Url::to(['tree/index', 'unit_id' => $data->id]), ['title' => 'Перейти в ' . $data->name]);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete} {add}',
                        'buttons' => [
                            'add' => function ($url, $model, $key) {
                                return ModalAjax::widget([
                                    'id' => 'addUnit' . $key,
                                    'header' => 'Создать дочерний элемент',
                                    'toggleButton' => [
                                        'label' => '',
                                        'class' => 'glyphicon glyphicon-plus',
                                        'title' => 'Создать дочерний элемент'
                                    ],
                                    'url' => '/tree/add?id=' . $key, // Ajax view with form to load
                                    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                                    'size' => ModalAjax::SIZE_LARGE,
                                    'options' => ['class' => 'header-primary'],
                                    'autoClose' => true,
                                    'pjaxContainer' => '#grid-company-pjax',
                                    'events' => [
                                        ModalAjax::EVENT_MODAL_SUBMIT => new \yii\web\JsExpression("function(event, data, status, xhr, selector) {window.location.reload();}")
                                    ]
                                ]);
                                // return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url);
                            },
                            'update' => function ($url, $model, $key) {
                                return ModalAjax::widget([
                                    'id' => 'updateUnit' . $key,
                                    'header' => 'Изменить подразделение',
                                    'toggleButton' => [
                                        'label' => '',
                                        'class' => 'glyphicon glyphicon-pencil',
                                        'title' => 'Изменить подразделение'
                                    ],
                                    'url' => '/tree/update?id=' . $key, // Ajax view with form to load
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
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<button class="glyphicon glyphicon-trash"></button>', ['delete', 'id' => $key], [
                                    'class' => 'file-item-dropdown-menu',
                                    'title' => 'Удалить',
                                    'data' => [
                                        'confirm' => 'Вы действительно хотите удалить ' . $model->name . '?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                        ]
                    ]
                ]
            ]);
            ?>                       
        </div>



        <?= ConnectionsGridWidget::widget(['connections' => $connections, 'child_units' => $child_units, 'parent_id' => $parent_id, 'unit_name' => $unit_name,
            'unit_id' => $unit_id_]);
        ?>


<?php Pjax::end(); ?>   


    </div>
</div>
