<?php

use yii\helpers\Html;
use yii\helpers\Url;
use lo\widgets\modal\ModalAjax;
?>

<div class="file-manager-container file-manager-col-view col-md-7">


    <?php
    if ($parent_id !== null) {

        echo '<div class="file-item">

    <a href= ' . Url::to(['tree/index', 'unit_id' => $parent_id]) . ' class="file-item-name">            

        <div class="file-item-icon file-item-level-up fas fa-level-up-alt text-secondary"></div>            

                ..

            </a>

    </div>';
    }
    ?>


    <?php
    foreach ($child_units as $child_unit) {
        ?>

        <div class="file-item">

            <div class="file-item-select-bg bg-primary"></div>

            <a href=<?= Url::to(['tree/index', 'unit_id' => $child_unit->id]) ?> class="file-item-name">

                <div class="file-item-icon far fa-folder text-secondary"></div>            

                <?= $child_unit->name ?>

            </a>            

            <div class="file-item-actions btn-group">

                <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>

                <div class="dropdown-menu dropdown-menu-right">

                    <a href="#" onclick=<?= "$('#updateUnit" . $child_unit->id . "').modal();" ?> class="file-item-dropdown-menu glyphicon glyphicon-pencil btn btn-info"></a>

                    <?=
                    Html::a('', ['delete', 'id' => $child_unit->id, 'from_tree' => 1, 'unit_id' => $child_unit->parent_id], [
                        'class' => 'file-item-dropdown-menu glyphicon glyphicon-trash btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы действительно хотите удалить ' . $child_unit->name . '?',
                            'method' => 'post',
                        ],
                    ])
                    ?>

                </div>

            </div>

        </div>


        <?php
    }
    ?>

    <?php
    foreach ($connections as $connection) {
        ?>
        <?php
        echo
        ModalAjax::widget([
            'id' => 'updateConnection' . ($connection->id),
            'header' => 'Изменить подключение <b>' . $connection->name . '</b>',
            'url' => '/connections/update?id=' . $connection->id, // Ajax view with form to load
            'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
            'size' => ModalAjax::SIZE_LARGE,
            'options' => ['class' => 'header-primary'],
            'autoClose' => true,
            'pjaxContainer' => '#grid-company-pjax',
            'events' => [
                ModalAjax::EVENT_MODAL_SUBMIT => new \yii\web\JsExpression("function(event, data, status, xhr, selector) {window.location.reload();}")
            ]
        ]);

        /* Html::a('', ['/connections/update', 'id' => $connection->id,], [
          'class' => 'file-item-dropdown-menu glyphicon glyphicon-trash btn btn-danger'

          ]) */
        ?>

        <div class="file-item">

            <div class="file-item-select-bg bg-primary"></div>



    <?php
    if ($connection->deviceType->optionalConnectionType === null) {

        echo '<a href="' . $connection->deviceType->defaultConnectionType->protocol_link . $connection->ipaddr . '">

<div class="file-item-img" style="background-image: url(' . '/icons-remote/no.jpg);"></div>			

<div class="file-item-img" style="background-image: url(' . '/icons-remote/thumb/' . $connection->deviceType->defaultConnectionType->icon . ');"></div>

			</a>';

        //echo '<img class="img-responsive" src="/icons-remote/no.jpg"></img>';
    } else {

        echo '<a href="' . $connection->deviceType->defaultConnectionType->protocol_link . $connection->ipaddr . '">

			<div class="file-item-img" style="background-image: url(' . '/icons-remote/thumb/' . $connection->deviceType->defaultConnectionType->icon . ');"></div>

			</a>';

        echo '<a href="' . $connection->deviceType->optionalConnectionType->protocol_link . $connection->ipaddr . '">

			<div class="file-item-img" style="background-image: url(' . '/icons-remote/thumb/' . $connection->deviceType->optionalConnectionType->icon . ');"></div>

			</a>';
    }
    ?>

            <hr class="m-0" />

    <?= '<a class="file-item-name" href="' . $connection->deviceType->defaultConnectionType->protocol_link . $connection->ipaddr . '">' ?>

            <?= $connection->name ?></a>


            <div class="file-item-actions btn-group">

                <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>

                <div class="dropdown-menu dropdown-menu-right">
                 
                    <a href="#" onclick=<?= "$('#updateConnection" . $connection->id . "').modal();" ?> class = "file-item-dropdown-menu glyphicon glyphicon-pencil btn btn-info"></a>

    <?=
    Html::a('', ['/connections/delete', 'id' => $connection->id, 'from_tree' => 1, 'unit_id' => $connection->unit_id], [
        'class' => 'file-item-dropdown-menu glyphicon glyphicon-trash btn btn-danger',
        'data' => [
            'confirm' => 'Вы действительно хотите удалить ' . $connection->name . '?',
            'method' => 'post',
        ],
    ])
    ?>

                </div>

            </div>

        </div>

    <?php
}
?>



</div>
