<?php

use app\components\connectionswidget\ConnectionsGridWidget;
use app\models\ConnectionTypes;
use app\models\Units;
use leandrogehlen\treegrid\TreeGrid;
use lo\widgets\modal\ModalAjax;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* * *ext** */


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model_search app\models\SearchForm */

$this->title = 'Адресная книга';
?>


<div class="tree-index">
    <div class="row">

        <?php Pjax::begin(); ?>
        <!-- Хлебные крошки -->
        <ul class="breadcrumb">
            <?= Units::unitsBreadCrumbs($unit_id_); ?>
        </ul>

        <div class="col-md-5 col-sm-12 col-12 noscrollbar table-responsive" style="overflow-y: scroll;
             height: 700px;">
            <!-- Если нет корневого элемента, то отражаем кнопку Создать корневой элемент -->
            <?php
            if ($dataProvider->count == 0)
                echo "<p>" . Html::a('Создать корневой элемент', ['add'], ['class' => 'btn btn-success']) . "</p>";
            ?>
            <!-- Левый блок с иерархией -->
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
                        [
                            'label' => 'Иерархия',
                            'value' => function (\app\models\Units $data) {
                                return Html::a(
                                    Html::encode($data->name),
                                    Url::to(['tree/index', 'unit_id' => $data->id]),
                                    [
                                        'id' => 'tree-link' . $data->id, 'title' => 'Перейти в ' . $data->name,
                                        'onclick' => 'return saveScroll(this);', //'class' => 'tregrid-col-with-edit'
                                    ]
                                );
                            },
                            'format' => 'raw',
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'visible' => $editing,
                            'contentOptions' => ['style' => 'min-width: 115px'],
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
                                        'header' => 'Изменить подразделение <u>' . $model->name . '</u>',
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
                                    $request = Yii::$app->request;
                                    $unit_id = $request->get('unit_id');
                                    return $model->parent_id ? Html::a('<button class="glyphicon glyphicon-trash"></button>', ['delete', 'id' => $key, 'from_tree' => 1, 'unit_id' => $unit_id], [
                                        //'class' => 'file-item-dropdown-menu',
                                        'onclick' => 'return saveScroll(this);',
                                        'title' => 'Удалить',
                                        'data' => [
                                            'confirm' => 'Вы действительно хотите удалить ' . $model->name . '?',
                                            'method' => 'post',
                                        ],
                                    ]) : "";
                                },
                            ]
                        ]
                    ]
            ]);
            ?>
        </div>

        <!-- Верхний блок с фильтрами-->
        <div class="col-md-7 col-sm-12  col-12 search-block">
            <div class="content-header-wrapper">
                <h3 class="title"><?php echo $unit_name; ?></h3>
            </div>

            <div class="content-header-wrapper">

                <!-- Форма поиска папок и подключений -->
                <div class="form-group col-sm-5 ">

                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'search-form',
                        //'class' => 'form-group col-sm-5 col-5'
                    ]);
                    ?>


                    <div class="row">
                        <?= $form->field($model_search, 'keyword', [
                            'options' => ['class' => 'form-group col-sm-10 col-md-10 col-lg-11']
                        ])->textInput([
                            'autofocus' => false,                         //'class' => 'form-control input-group'
                        ])->label(false) ?>

                        <div class="dropdown col-sm-2 col-md-2 col-lg-1">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Подключиться по
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <?php
                                $connectionsTypes = ConnectionTypes::find()->all();
                                $numItems = count($connectionsTypes);
                                $i = 0;
                                foreach ($connectionsTypes as $connectionsType) {
                                    echo '<li><a href="#" onclick="quickConnect(\'' . $connectionsType->protocol_link . '\')">' . $connectionsType->name . '</a></li>';
                                    //echo $connectionsType->protocol_link;
                                    ?>
                                    <?= $connectionsType->protocol_link_readonly !== null ? '<li><a href="#" onclick="quickConnect(\'' . $connectionsType->protocol_link_readonly . '\')">' . $connectionsType->name . ': просмотр</a></li>' : "" ?>
                                    <?php
                                    if (++$i !== $numItems) {
                                        echo '<li role="separator" class="divider"></li>';
                                    }
                                }

                                ?>
                            </ul>
                        </div>
                        <!-- Окончание выпадающего списка быстрого подключения по известным протоколам.-->


                    </div>


                    <div class="form-inline ">


                        <?= $form->field($model_search, 'byipsearch')->checkbox(['style' => 'mardin-left:10px;margin-top:-10px ;margin-bottom:10px']); ?>
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary', 'name' => 'search-button', 'value' => 'btn-name', 'style' => 'margin-left:30px;margin-top:-10px ;margin-bottom:10px']) ?>

                    </div>


                    <?php ActiveForm::end(); ?>
                </div>
                <!-- Кнопки вида и создания нового подключения -->
                <div class="actions">
                    <div class="page-nav">

                        <label>Редактор: </label>
                        <div class="btn-group btn-toggle">
                            <?php
                            if ($editing) {
                                echo "<button class='btn btn-sm btn-primary active'
                                        onclick='window.location.href=\"index?unit_id=$unit_id_&editing=1&changeEditing=1\"'
                                        title='Просмотр папок и подключений с возможностью их редактировать'>ВКЛ</button>
                                      <button class='btn btn-sm btn-default'
                                        onclick='window.location.href =\"index?unit_id=$unit_id_&editing=0&changeEditing=1\"'
                                        title='Только просмотр папок и подключений'>ВЫКЛ</button>";
                            } else {
                                echo "<button class='btn btn-sm btn-default'
                                        onclick='window.location.href=\"index?unit_id=$unit_id_&editing=1&changeEditing=1\"'
                                        title='Просмотр папок и подключений с возможностью их редактировать'>ВКЛ</button>
                                      <button class='btn btn-sm btn-primary active'
                                        onclick='window.location.href=\"index?unit_id=$unit_id_&editing=0&changeEditing=1\"'
                                        title='Только просмотр папок и подключений'>ВЫКЛ</button>";
                            }
                            ?>
                        </div>

                        &nbsp;&nbsp;<label class="indicator">Вид: </label>
                        <div class="btn-group" role="group" data-toggle="buttons">
                            <?php
                            if ($view_type == "icons") {
                                echo "
                                        <label class='btn btn-default' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=grid&change_view=1&admin=0&changeAdmin=1\"' title='Списком'>
                                            <input type='radio' name='options' id='option2' autocomplete='off'><i class='fa fa-list-ul'></i>
                                        </label>
                                        <label class='btn btn-default active' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=icons&change_view=1&admin=0&changeAdmin=1\"' title='Иконками'>
                                            <input type='radio' name='options' id='option1' autocomplete='off' checked><i class='fa fa-th-large'></i>
                                        </label>
                                        <label class='btn btn-default' onclick='window.location.href =\"index?unit_id=$unit_id_&admin=1&changeAdmin=1&view_type=grid&change_view=1\"' title='Админ режим'>
                                            <input type='radio' name='options' id='option2' autocomplete='off'><i class='fa fa-terminal'></i>
                                        </label>
                                        ";
                            } else {
                                echo "
                                        <label class='btn btn-default active' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=grid&change_view=1&admin=0&changeAdmin=1\"' title='Списком'>
                                            <input type='radio' name='options' id='option2' autocomplete='off' checked><i class='fa fa-list-ul'></i>
                                        </label>
                                        <label class='btn btn-default' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=icons&change_view=1&admin=0&changeAdmin=1\"' title='Иконками'>
                                            <input type='radio' name='options' id='option1' autocomplete='off'><i class='fa fa-th-large'></i>
                                        </label>
                                        <label class='btn btn-default' onclick='window.location.href =\"index?unit_id=$unit_id_&admin=1&changeAdmin=1&view_type=grid&change_view=1\"' title='Админ режим'>
                                             <input type='radio' name='options' id='option2' autocomplete='off'><i class='fa fa-terminal'></i>
                                        </label>
                                        ";
                            }
                            ?>
                        </div>
                    </div>


                    <?php
                    if ($editing) {
                        echo
                            '<button class="btn btn-success" onclick="$(\'#createConnection' . $unit_id_ . '\').modal();"  title="Создать новое подключение">
                        <i class="fa fa-plus"></i> Создать подключение </button>' .
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
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Блок с папками и блок  с подключениями в виде Gridview -->
        <div class="col-md-7 table-responsive drive-items-table-wrapper" style="overflow-y: scroll;
             height: 575px;
             /*scrollbar-width: none;*/
             ">

            <?php
            if ($view_type == "icons") {
                echo ConnectionsGridWidget::widget([
                    'connections' => $connections->all(),
                    'child_units' => $child_units->all(),
                    'parent_id' => $parent_id,
                    'unit_name' => $unit_name,
                    'unit_id' => $unit_id_,
                    'editing' => $editing
                ]);
            } else {
                //Сначала папки, дабы не уезжало далеко, сделана пагинация на 5 папок
                echo '<h4>Список дочерних папок / Список найденных папок при поиске</h4>';
                echo GridView::widget(
                    [
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $child_units,
                            'sort' =>false,
                            /*'pagination' => [
                                'pageSize' => 5
                            ],*/
                        ]),

                        'layout' => "\n{items}",
                        'columns' =>
                            [
                                [
                                    'attribute' => 'name',
                                    'value' => function (\app\models\Units $data, $view_type) {
                                        return Html::a(
                                            Html::encode($data->name),
                                            Url::to(['tree/index', 'unit_id' => $data->id, 'view_type' => $view_type]),
                                            [
                                                'id' => 'tree-link' . $data->id, 'title' => 'Перейти в ' . $data->name,
                                                'onclick' => 'return saveScroll(this);'
                                            ]
                                        );
                                    },
                                    'format' => 'raw',
                                    'label' => 'Дочерние папки'
                                ],
                            ]
                    ]
                );
                //Затем подключения
                echo '<p></p><h4>Список подключений / Список найденных подключений при поиске</h4>';
                ?>
                <p>

                    <?php
                    if ($parent_id !== null) {
                        echo
                            '<a href=\'' . Url::to(['tree/index', 'unit_id' => $parent_id]) .
                            '\' title="Вверх" class="btn btn-info glyphicon glyphicon-level-up"></a>';
                    }
                    ?>
                    <button class="btn btn-success" onclick="checkOnline()"
                            title="[медленно! не жми в больших папках, займёт > 3 минут], работает только в виде список">
                        <img class="buttonAllCheck" src="/images/reload.png" height=20px"> Проверить online у всех
                        [медленно! не жми в больших папках, займёт > 3 минут]
                    </button>
                </p>
                <?php
                echo GridView::widget(
                    [
                        'dataProvider' => new ActiveDataProvider([
                            'query' => $connections,
                            'pagination' => [
                                'pageSize' => 0, // ALL results, no pagination
                            ],
                            'sort' =>false,
                        ]),
                        'tableOptions' => [
                            'class' => 'table table-striped table-bordered',
                            //'style' => 'width:100px'
                        ],
                        'layout' => "\n{items}{summary}",
                        'columns' =>
                            [
                                [
                                    //'attribute' => 'name',
                                    'label' => 'Имя',
                                    'contentOptions' => ['style' => 'max-width: 180px; mix-width: 180px;'],
                                    'value' =>
                                        function ($data) {

                                            if (str_contains(Yii::$app->request->url, 'unit_id=' . $data->unit_id)) {
                                                return $data->hostname ? "<p><b id='hostname'>" . $data->hostname . "</b> " . str_replace("<p>", "", $data->name) : $data->name;
                                            } else {
                                                $nameText = $data->hostname ? "<p><b id='hostname'>" . $data->hostname . "</b> " . str_replace("<p>", "", $data->name) : $data->name;
                                                return $nameText . '<div align="right">' .
                                                    Html::a(
                                                    //'<button class="glyphicon glyphicon-folder-open"></button>
                                                        ' <button type="button" class="btn btn-sm btn-warning">
                                                    <span class="glyphicon glyphicon-folder-open"></span>
                                              </button>',
                                                        Url::to(['tree/index', 'unit_id' => $data->unit_id]),
                                                        [
                                                            'id' => 'tree-link' . $data->unit_id, 'title' => 'Перейти в папку c подключением',
                                                            'onclick' => 'return saveScroll(this);'
                                                        ]
                                                    ) . '</div>'// "&nbsp;&nbsp;&nbsp;" . $data->name
                                                    ;
                                            }
                                        },
                                    'format' => 'raw',
                                ],
                                [
                                    'contentOptions' => ['style' => 'max-width: 125px; word-wrap: break-word;'],
                                    'value' => function ($data) {
                                        $name = $data->deviceType->defaultConnectionType->name;
                                        $defaultLink = $data->deviceType->defaultConnectionType->protocol_link . $data->ipaddr;
                                        $defaultLinkViewOnly = (!empty($data->deviceType->defaultConnectionType->protocol_link_readonly)) ?
                                            Html::a("<i class='fa fa-eye' aria-hidden='true'  title='только просмотр'></i>", $data->deviceType->defaultConnectionType->protocol_link_readonly
                                                . $data->ipaddr) . "&nbsp;&nbsp;" : "";

                                        if ($data->deviceType->optionalConnectionType !== null) {
                                            $optionalLink = $data->deviceType->optionalConnectionType->protocol_link . $data->ipaddr;
                                            $nameOptional = $data->deviceType->optionalConnectionType->name;
                                            $optionalLinkViewOnly = (!empty($data->deviceType->optionalConnectionType->protocol_link_readonly)) ?
                                                Html::a("<i class='fa fa-eye' aria-hidden='true'  title='только просмотр'></i>", $data->deviceType->optionalConnectionType->protocol_link_readonly
                                                    . $data->ipaddr) . "&nbsp;&nbsp;" : "";
                                            return
                                                Html::a($name, $defaultLink, [
                                                    'id' => 'ipaddr-remote', 'title' => 'Подключиться в режиме управления',
                                                    'onclick' => "sendStats('" . $data->id . "')"
                                                ])
                                                . "&nbsp;"
                                                . $defaultLinkViewOnly
                                                . "&nbsp;&nbsp;&nbsp;&nbsp;"
                                                . Html::a($nameOptional, $optionalLink, [
                                                    'id' => 'ipaddr-remote', 'title' => 'Подключиться в режиме управления',
                                                    'onclick' => "sendStats('" . $data->id . "')"
                                                ])
                                                . "&nbsp;"
                                                . $optionalLinkViewOnly
                                                . '<button class="" onclick="checkOnlineRow(\'' . $data->id . '\', \'' . $defaultLink . '\', \'' . $optionalLink . '\')">' .
                                                '<img class="button link' . $data->id . '" src="/images/reload.png" height=15px"></button></div>';
                                        } else
                                            return
                                                Html::a($name, $defaultLink, [
                                                    'id' => 'ipaddr-remote', 'title' => 'Подключиться в режиме управления',
                                                    'onclick' => "sendStats('" . $data->id . "')"
                                                ])
                                                . "&nbsp;"
                                                . $defaultLinkViewOnly
                                                .
                                                '<button class="" onclick="checkOnlineRow(\'' . $data->id . '\', \'' . $defaultLink . '\')">' .
                                                '<img class="button link' . $data->id . '" src="/images/reload.png" height=15px"></button></div>';
                                    },
                                    //'attribute' => '',
                                    'format' => 'raw',
                                    'label' => 'Подключиться по',
                                ],
                                [
                                    'attribute' => 'ipaddr',
                                    'contentOptions' => ['style' => 'max-width: 90px;'],
                                    'format' => 'raw',
                                    'value' => function ($data) {
                                        $duplicates = $data::connectionsByiP($data->ipaddr, $data->id)->all();
                                        if (count($duplicates) == 0) {
                                            return $data->ipaddr;
                                        } else {
                                            $request = Yii::$app->request;
                                            $unit_id = $request->get('unit_id');
                                            $text = $data->ipaddr;
                                            $text = $text . '&nbsp;&nbsp;' .

                                                Html::a('<button title="У этого IP есть дубли, нажмите для поиска всех соединений с этим IP!" type="button" class="btn btn-sm btn-danger">
                                        <span class="glyphicon glyphicon-search"></span></button>', ['/tree/index?unit_id=' . $unit_id], [
                                                    'data' => [
                                                        'method' => 'POST',
                                                        'params' => [
                                                            'SearchForm[byipsearch]' => 1,
                                                            'SearchForm[keyword]' => $data->ipaddr,
                                                            'search-button' => 'btn-name'
                                                        ],
                                                    ],
                                                ]);
                                            return $text;
                                        };
                                    }
                                ],
                                [
                                    'attribute' => 'device_type_id',
                                    'value' => function ($model, $key, $index, $widget) {
                                        return $model->deviceType->name;
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'visible' => $editing,
                                    'contentOptions' => ['style' => 'min-width: 45px; max-width: 65px'],
                                    'template' => '{update} {delete} {copy}',
                                    'buttons' => [
                                        'update' => function ($url, $model, $key) {
                                            return ModalAjax::widget([
                                                'id' => 'updateConnection' . ($model->id),
                                                'header' => 'Изменить подключение <u>' . $model->name . '</u>',
                                                'url' => '/connections/update?id=' . $model->id, // Ajax view with form to load
                                                'toggleButton' => [
                                                    'label' => '',
                                                    'class' => 'glyphicon glyphicon-pencil',
                                                    'title' => 'Изменить подключение'
                                                ],
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
                                            $name = strip_tags($model->name);
                                            return Html::a('<button class="glyphicon glyphicon-trash"></button>', ['/connections/delete', 'id' => $model->id, 'from_tree' => 1, 'unit_id' => $model->unit_id], [
                                                'title' => 'Удалить подключение',
                                                'data' => [
                                                    'confirm' => 'Вы действительно хотите удалить ' . $name . '?',
                                                    'method' => 'post',
                                                ],
                                            ]);
                                        },

                                        'copy' => function ($url, $model, $key) {
                                            return ModalAjax::widget([
                                                'id' => 'copyConnection' . ($model->id),
                                                'header' => 'Копировать подключение',
                                                'url' => '/connections/copy?id=' . $model->id, // Ajax view with form to load
                                                'toggleButton' => [
                                                    'label' => '',
                                                    'class' => 'glyphicon glyphicon-plus',
                                                    'title' => 'Копировать подключение'
                                                ],
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

                                    ]
                                ]
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


<?php
$updateScript = <<< JS

function checkOnline()
{
    //jQuery('.button').addClass('loading');
    //var links = [];
    // перебрать элементы div на странице
	$('a#ipaddr-remote').each(function (index, element)
	{
        $('.buttonAllCheck').attr('class', 'buttonAllCheck loading');
        //links.push($(this).attr('href'));
		// index (число) - текущий индекс итерации (цикла)
		// данное значение является числом
		// начинается отсчёт с 0 и заканчивается количеству элементов в текущем наборе минус 1
		// element - содержит DOM-ссылку на текущий элемент
		var link = $(this).attr('href');
		//console.log(link);
		$.post('check', {link: link}, function(data)
		{   $('.buttonAllCheck.loading').attr('class', 'buttonAllCheck');
			if (data.checkResult == true)
				{
					$(element).attr('class', 'ip-addr-ready');
				} else {
                        $(element).attr('class', 'ip-addr-noready');
                }
		});

	});

    /*$.ajax({
			url: 'check',
			method: 'post',
			dataType: 'json',
			data: {links: links},
            async:true,
			success: function(data)
				{
                    $('.button.loading').attr('class', 'button');
					if (data.checkResult == true)
						{

							//$(element).attr('class', 'ip-addr-ready');
						};
				}
			});*/


    //jQuery('.button').removeClass('loading');
};


function checkOnlineRow(linkId ,firstLink,  secondLink = "" )
{

        jQuery('.button.link' + linkId).addClass('loading');
        $.post('check', {link: firstLink}, function(data){
            if (data.checkResult == true)
				{
                    $('a#ipaddr-remote').each(function (index, element){
                        var link = $(this).attr('href');
                        if (link == firstLink)
                        {
                            $(element).attr('class', 'ip-addr-ready');
                        }
                    })

				}
                else {
                    $('a#ipaddr-remote').each(function (index, element){
                        var link = $(this).attr('href');
                        if (link == firstLink)
                        {
                            $(element).attr('class', 'ip-addr-noready');
                        }
                    })
                }
        });
        if (secondLink !== "")
        {
            $.post('check', {link: secondLink}, function(data){
            if (data.checkResult == true)
				{
                    $('a#ipaddr-remote').each(function (index, element){
                        var link = $(this).attr('href');
                        if (link == secondLink)
                        {
                            $(element).attr('class', 'ip-addr-ready');
                        }
                    })

				}
                else {
                    $('a#ipaddr-remote').each(function (index, element){
                        var link = $(this).attr('href');
                        if (link == secondLink)
                        {
                            $(element).attr('class', 'ip-addr-noready');
                        }
                    })
                }
        });
        }
        setTimeout(function() {
            jQuery('.button.link' + linkId).removeClass('loading');
}       , 1500);

    //var links = [];
    // перебрать элементы div на странице


	/*
        $('.button').attr('class', 'button loading');
        //links.push($(this).attr('href'));
		// index (число) - текущий индекс итерации (цикла)
		// данное значение является числом
		// начинается отсчёт с 0 и заканчивается количеству элементов в текущем наборе минус 1
		// element - содержит DOM-ссылку на текущий элемент
		var link = $(this).attr('href');
		//console.log(link);
		$.post('check', {link: link}, function(data)
		{   $('.button.loading').attr('class', 'button');
			if (data.checkResult == true)
				{
					$(element).attr('class', 'ip-addr-ready');
				};
		});

	});  */
};


function ValidateIPaddress(ipaddress) {
  if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)) {
    return (true)
  }
  alert("Введенный вами адрес не является IP-адресом!")
  return (false)
}


function quickConnect(protocolLink)
{
    searchformKeyword = document.getElementById('searchform-keyword');
    ipaddress = searchformKeyword.value;
    if (ValidateIPaddress(ipaddress))
        {
            window.open(protocolLink + ipaddress ,"_self");
        }

}

function sendStats(connectionId)
{
    //alert(connectionId);
    //$.post('stats', {connectionId: connectionId});
    $.post('stats', {connectionId: connectionId}, function(data){
    });
}

JS;

$this->registerJs($updateScript, yii\web\View::POS_END);
?>
