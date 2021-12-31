<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use app\components\connectionswidget\ConnectionsGridWidget;
use lo\widgets\modal\ModalAjax;
/* * *ext** */
use leandrogehlen\treegrid\TreeGrid;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model_search app\models\SearchForm */

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
             style="overflow-y: scroll;
             height: 700px;
             /*scrollbar-width: none;*/
             ">
            <!-- стиль, чтобы убрать полосу прокрутки-->
            <style>
                /*.noscrollbar::-webkit-scrollbar {
                    width: 0px;
                }*/
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
                            return Html::a(Html::encode($data->name),
                                    Url::to(['tree/index', 'unit_id' => $data->id]),
                                    ['id' => 'tree-link' . $data->id, 'title' => 'Перейти в ' . $data->name,
                                        'onclick' => 'return saveScroll(this);'
                            ]);
                        },
                        'format' => 'raw',
                    ],
                /* ['class' => 'yii\grid\ActionColumn',
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
                  ] */
                ]
            ]);
            ?>                       
        </div>

        <!-- Верхний блок с фильтрами-->
        <div class="col-md-7 search-block">
            <div class="content-header-wrapper">
                <h3 class="title"><?php echo $unit_name; ?></h3>                
            </div>        
            <div class="content-header-wrapper">

                <!-- Форма поиска папок и подключений -->
                <?php
                $form = ActiveForm::begin([
                            'id' => 'search-form',
                ]);
                ?>                
                <div class="form-group">
                    <div class="col-sm-5">
                        <?= $form->field($model_search, 'keyword')->textInput(['autofocus' => false])->label(false) ?>                                
                        <?= $form->field($model_search, 'byipsearch')->checkbox(); ?> 
                    </div>
                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary', 'name' => 'search-button', 'value' => 'btn-name',]) ?> 
                </div>           
                <?php ActiveForm::end(); ?>

                <!-- Кнопки вида и создания нового подключения -->
                <div class="actions">
                    <div class="page-nav">
                        <span class="indicator">Вид: </span>
                        <div class="btn-group" role="group"  data-toggle="buttons">
                            <?php
                            if ($view_type == "icons") {
                                echo "
                       <label class='btn btn-default active' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=icons&change_view=1\"' title='Иконками'>
    <input type='radio' name='options' id='option1' autocomplete='off' checked
    ><i class='fa fa-th-large'></i>
  </label>
  <label class='btn btn-default' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=grid&change_view=1\"' title='Списком'>
    <input type='radio' name='options' id='option2' autocomplete='off'
    ><i class='fa fa-list-ul'></i>
  </label>";
                            } else {
                                echo "
                       <label class='btn btn-default' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=icons&change_view=1\"' title='Иконками'>
    <input type='radio' name='options' id='option1' autocomplete='off' 
     ><i class='fa fa-th-large'></i>
  </label>
  <label class='btn btn-default active' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=grid&change_view=1\"' title='Списком'>
    <input type='radio' name='options' id='option2' autocomplete='off' checked 
    ><i class='fa fa-list-ul'></i>
  </label>";
                            }
                            ?>
                        </div>
                    </div>
                    <button class="btn btn-success" 
                            onclick=<?= "$('#createConnection" . $unit_id_ . "').modal();" ?> 
                            title = "Создать новое подключение">
                        <i class="fa fa-plus"></i> Создать подключение </button>
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
        </div>

        <!-- Блок с папками и блок  с подключениями в виде Gridview -->        
        <div class="col-md-7 table-responsive drive-items-table-wrapper" style="overflow-y: scroll;
             height: 575px;
             /*scrollbar-width: none;*/
             ">
            <style>
                .table-responsive tbody tr {
                    color: #5a5a5a; /* Цвет текста */
                    /*background: #ffc; /* Цвет фона */
                    padding: 10px; /* Поля вокруг текста */
                    transition: 0.25s linear; /* Время изменения */
                }
                .table-responsive  tbody tr:hover {
                    /*color: #fff; /* Цвет текста */
                    background: lightblue; /* Цвет фона */
                }
            </style>
            <!-- Сначала папки, дабы не уезжало далеко, сделана пагинация на 5 папок -->
            <h4>Список дочерних папок / Список найденных папок при поиске</h4>

            <?=
            GridView::widget(
                    [
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $child_units,
                            'pagination' => [
                                'pageSize' => 5
                            ],
                                ]),
                        //'layout' => "\n{items}\n{summary}\n{pager}",
                        'columns' =>
                        [
                            [
                                'attribute' => 'name',
                                'value' => function (\app\models\Units $data, $view_type) {
                                    return Html::a(Html::encode($data->name),
                                            Url::to(['tree/index', 'unit_id' => $data->id, 'view_type' => $view_type]),
                                            ['id' => 'tree-link' . $data->id, 'title' => 'Перейти в ' . $data->name,
                                                'onclick' => 'return saveScroll(this);'
                                    ]);
                                },
                                'format' => 'raw',
                                'label' => 'Дочерние папки'
                            ]
                        ]
                    ]
            )
            ?>
            <!-- Затем подключения -->
            <p></p>
            <h4>Список подключений / Список найденных подключений при поиске</h4>
            <?php
            if ($view_type == "icons") {
                echo ConnectionsGridWidget::widget(['connections' => $connections->all(), 'child_units' => $child_units->all(), 'parent_id' => $parent_id, 'unit_name' => $unit_name,
                    'unit_id' => $unit_id_]);
            }
            /* ListView::widget(
              ['dataProvider' => new ActiveDataProvider([
              'query' => $connections,
              'pagination' => [
              'pageSize' => 0, // ALL results, no pagination
              ],
              ]),
              'itemView' => '_list',
              'layout' => "\n{items}",
              'options' => [
              'tag' => 'table',
              'class' => 'table'
              ],
              'itemOptions' => [// опции для списка
              'tag' => 'tr', // заключаем список в тег div
              ],
              'beforeItem' => function ($model, $key, $index, $widget) {
              if ($index == 0) {
              return '<thead>
              <tr>
              <th class="name truncate">Имя</th>
              <th class="date">IP-адрес</th>
              <th colspan=2 class="size">Подключиться по</th>
              </tr>
              </thead>';
              }
              }
              ],
              ); */ else {
                echo GridView::widget(
                        ['dataProvider' => new ActiveDataProvider([
                                'query' => $connections,
                                'pagination' => [
                                    'pageSize' => 0, // ALL results, no pagination
                                ],
                                    ]),
                            'tableOptions' => [
                                'class' => 'table table-striped table-bordered'
                            ],
                            'layout' => "\n{items}",
                            'columns' =>
                            [
                                [
                                    'attribute' => 'name',
                                    'label' => 'Имя подключения'
                                ],
                                'ipaddr',
                                [
                                    'value' => function ($data) {
                                        if ($data->deviceType->optionalConnectionType !== null)
                                            return

                                                    Html::a($data->deviceType->defaultConnectionType->name, $data->deviceType->defaultConnectionType->protocol_link . $data->ipaddr) .
                                                    "&nbsp;&nbsp;&nbsp;" .
                                                    Html::a($data->deviceType->optionalConnectionType->name, $data->deviceType->optionalConnectionType->protocol_link . $data->ipaddr)
                                            ;
                                        else
                                            return Html::a($data->deviceType->defaultConnectionType->name, $data->deviceType->defaultConnectionType->protocol_link . $data->ipaddr);
                                    },
                                    //'attribute' => '',
                                    'format' => 'raw',
                                    'label' => 'Подключиться по',
                                ],
                                [
                                    'attribute' => 'device_type_id',
                                    'value' => function ($model, $key, $index, $widget) {
                                        return $model->deviceType->name;
                                    },
                                    'format' => 'raw',
                                ],
                            /* ['class' => 'yii\grid\ActionColumn',
                              'template' => '{update} {delete}',
                              'buttons' => [

                              ]
                              ] */
                            ]
                        ]
                );
            }
            ?>  
        </div>

        <?php Pjax::end(); ?>   


    </div>
</div>

<!-- JS-скрипт для сохранения позиции скролла в дереве -->
<?php
$script = <<< JS
    function loadScroll() {
        if (localStorage.getItem("treegrid-scroll") != null) {
            $(".noscrollbar").scrollTop(localStorage.getItem("treegrid-scroll"));
            //alert("scroll");
        }
    }; 
        
    function saveScroll()
    {
        localStorage.setItem("treegrid-scroll", $(".noscrollbar").scrollTop());
    };
    document.addEventListener('DOMContentLoaded', loadScroll);
    $(document).on('pjax:complete', function (){        
        if (localStorage.getItem("treegrid-scroll") != null) {
            $(".noscrollbar").scrollTop(localStorage.getItem("treegrid-scroll"));            
        }
    })
JS;

$this->registerJs($script, yii\web\View::POS_END);
?>
