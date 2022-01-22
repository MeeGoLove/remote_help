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
use app\models\Units;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model_search app\models\SearchForm */

$this->title = 'Адресная книга'; ?>


<div class="tree-index">
    <!-- Если нет корневого элемента, то отражаем кнопку Создать корневой элемент -->
    <?php
    if ($dataProvider->count == 0)
        echo "<p>" . Html::a('Создать корневой элемент', ['add'], ['class' => 'btn btn-success']) . "</p>";
    ?>


    <div class="row">

        <?php Pjax::begin(); ?>
        <!-- Хлебные крошки -->
        <ul class="breadcrumb">
            <?= Units::unitsBreadCrumbs($unit_id_); ?>
        </ul>

        <div class="col-md-5 col-sm-12 col-12 noscrollbar table-responsive" style="overflow-y: scroll;
             height: 700px;">
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
                                    $request = Yii::$app->request;
                                    $unit_id = $request->get('unit_id');
                                    return Html::a('<button class="glyphicon glyphicon-trash"></button>', ['delete', 'id' => $key, 'from_tree' => 1, 'unit_id' => $unit_id], [
                                        //'class' => 'file-item-dropdown-menu',
                                        'onclick' => 'return saveScroll(this);',
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


                    <?= $form->field($model_search, 'keyword')->textInput(['autofocus' => false])->label(false) ?>
                    <div class="form-inline ">
                        <?= $form->field($model_search, 'byipsearch')->checkbox(['style' => 'mardin-left:10px;margin-top:-10px ;margin-bottom:10px']); ?>
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary', 'name' => 'search-button', 'value' => 'btn-name', 'style' => 'margin-left:30px;margin-top:-10px ;margin-bottom:10px']) ?>
                    </div>




                    <?php ActiveForm::end(); ?>
                </div>
                <!-- Кнопки вида и создания нового подключения -->
                <div class="actions">
                    <div class="page-nav">

                        <label>Редактирование: </label>
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

                        &nbsp; &nbsp; <label class="indicator">Вид: </label>
                        <div class="btn-group" role="group" data-toggle="buttons">
                            <?php
                            if ($view_type == "icons") {
                                echo "
                                        <label class='btn btn-default' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=grid&change_view=1\"' title='Списком'>
                                            <input type='radio' name='options' id='option2' autocomplete='off'><i class='fa fa-list-ul'></i>
                                        </label>
                                        <label class='btn btn-default active' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=icons&change_view=1\"' title='Иконками'>
                                            <input type='radio' name='options' id='option1' autocomplete='off' checked><i class='fa fa-th-large'></i>
                                        </label>";
                            } else {
                                echo "
                                        <label class='btn btn-default active' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=grid&change_view=1\"' title='Списком'>
                                            <input type='radio' name='options' id='option2' autocomplete='off' checked><i class='fa fa-list-ul'></i>
                                        </label>
                                        <label class='btn btn-default' onclick='window.location.href =\"index?unit_id=$unit_id_&view_type=icons&change_view=1\"' title='Иконками'>
                                            <input type='radio' name='options' id='option1' autocomplete='off'><i class='fa fa-th-large'></i>
                                        </label>";
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

                <p><button class="btn btn-success" onclick="checkOnline()" title="[медленно! не жми в больших папках, займёт > 3 минут], работает только в виде список">
                        <img class="buttonAllCheck" src="/images/reload.png" height=20px"> Проверить online у всех [медленно! не жми в больших папках, займёт > 3 минут]</button></p>
            <?php
                echo GridView::widget(
                    [
                        'dataProvider' => new ActiveDataProvider([
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
                                'label' => 'Имя подключения',
                                'contentOptions' => ['style' => 'max-width: 180px; word-wrap: break-word'],
                            ],
                            [
                                'attribute' => 'ipaddr',
                                //'contentOptions' => ['id' => 'ipaddr-remote']
                            ],
                            [
                                'value' => function ($data) {
                                    $name = $data->deviceType->defaultConnectionType->name;
                                    $defaultLink = $data->deviceType->defaultConnectionType->protocol_link . $data->ipaddr;
                                    if ($data->deviceType->optionalConnectionType !== null) {
                                        $optionalLink = $data->deviceType->optionalConnectionType->protocol_link . $data->ipaddr;
                                        $nameOptional = $data->deviceType->optionalConnectionType->name;
                                        return
                                            Html::a($name, $defaultLink, ['id' => 'ipaddr-remote']) .
                                            "&nbsp;&nbsp;&nbsp;" .
                                            Html::a($nameOptional, $optionalLink, ['id' => 'ipaddr-remote']) .
                                            '&nbsp;&nbsp;<button class="" style="width:26px;" onclick="checkOnlineRow(\'' . $data->id . '\', \'' . $defaultLink . '\', \'' . $optionalLink . '\')">' .
                                            '<img class="button link' . $data->id . '" src="/images/reload.png" height=15px"></button></div>';
                                    } else
                                        return
                                            Html::a($name, $defaultLink, ['id' => 'ipaddr-remote']) .
                                            '&nbsp;&nbsp;<button class="" style="width:26px;" onclick="checkOnlineRow(\'' . $data->id . '\', \'' . $defaultLink . '\')">' .
                                            '<img class="button link' . $data->id . '" src="/images/reload.png" height=15px"></button></div>';
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
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'visible' => $editing,
                                'contentOptions' => ['style' => 'min-width: 85px'],
                                'template' => '{update}{delete}',
                                'buttons' => [
                                    'update' => function ($url, $model, $key) {
                                        return ModalAjax::widget([
                                            'id' => 'updateConnection' . ($model->id),
                                            'header' => 'Изменить подключение <b>' . $model->name . '</b>',
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
                                        return Html::a('<button class="glyphicon glyphicon-trash"></button>', ['/connections/delete', 'id' => $model->id, 'from_tree' => 1, 'unit_id' => $model->unit_id], [

                                            'title' => 'Удалить',
                                            'data' => [
                                                'confirm' => 'Вы действительно хотите удалить ' . $model->name . '?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                    }

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

JS;

$this->registerJs($updateScript, yii\web\View::POS_END);
?>
