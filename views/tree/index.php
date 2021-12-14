<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
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
        <!-- Верхний блок с фильтрами-->
        <div class="col-md-7">
            <div class="content-header-wrapper">
                <h2 class="title"><?php echo $unit_name; ?></h2>
                <div class="actions">
                    <button class="btn btn-success" onclick=<?= "$('#createConnection" . $unit_id_ . "').modal();" ?>><i class="fa fa-plus"></i> Новое подключение </button>
                    <?php
                    echo
                    ModalAjax::widget([
                        'id' => 'createConnection' . ($unit_id_),
                        'header' => 'Создать новое подключение',
                        'url' => '/connections/create?unit_id=' . $unit_id_, // Ajax view with form to load
                        'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                        'size' => ModalAjax::SIZE_LARGE,
                        'options' => ['class' => 'header-primary'],
                        'autoClose' => true,
                        'pjaxContainer' => '#grid-company-pjax',
                        'events' => [
                            ModalAjax::EVENT_MODAL_SUBMIT => new \yii\web\JsExpression("function(event, data, status, xhr, selector) {window.location.reload();}")
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="content-header-wrapper">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'search-form',
                            //'layout' => 'horizontal',
                            //'fieldConfig' => [
                                //'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                                //'labelOptions' => ['class' => 'col-lg-1 control-label'],
                            //],
                ]);
                ?>

                <?= $form->field($model_search, 'keyword')->textInput(['autofocus' => true]) ?>               

                

                <div class="form-group">
                    <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Искать', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>

<?php ActiveForm::end(); ?>

            </div>
            <div class="content-utilities">
                <div class="page-nav">
                    <span class="indicator">Вид: </span>
                    <div class="btn-group" role="group">
                        <button class="active btn btn-default" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Таблица" title="Таблица" id="drive-grid-toggle"><i class="fa fa-th-large"></i></button>
                        <button class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Список" title="Список" id="drive-list-toggle"><i class="fa fa-list-ul"></i></button>
                    </div>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button" aria-expanded="false">Все файлы... <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="fa fa-file"></i> Документы </a></li>
                            <li><a href="#"><i class="fa fa-file-image-o"></i> Картинки</a></li>
                            <li><a href="#"><i class="fa fa-file-video-o"></i> Ля</a></li>
                            <li><a href="#"><i class="fa fa-folder"></i> Папки</a></li>
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button" aria-expanded="false"><i class="fa fa-filter"></i> Сортировка? <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="#">И?</a></li>
                            <li><a href="#">И?</a></li>
                        </ul>
                    </div>
                    <!--<div class="btn-group" role="group">
                        <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Refresh"><i class="fa fa-refresh"></i></button>
                        <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Archive"><i class="fa fa-archive"></i></button>
        
                        <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Report spam"><i class="fa fa-exclamation-triangle"></i></button>
                        <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Delete"><i class="fa fa-trash-o"></i></button>
                    </div>-->
                </div>
            </div>
        </div>


<?=
ConnectionsGridWidget::widget(['connections' => $connections, 'child_units' => $child_units, 'parent_id' => $parent_id, 'unit_name' => $unit_name,
    'unit_id' => $unit_id_]);
?>


<?php Pjax::end(); ?>   


    </div>
</div>
