<?php

use yii\helpers\Url;
?>
<div class="file-manager-container file-manager-col-view col-md-8">

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
                    <a class="file-item-dropdown-menu" href="javascript:void(0)">Переименовать</a>
                    <a class="file-item-dropdown-menu" href="javascript:void(0)">Удалить</a>
                </div>
            </div>
        </div>

        <?php
    }
    ?>
    <?php
    foreach ($connections as $connection) {
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
            <?= '<a href="' . $connection->deviceType->defaultConnectionType->protocol_link . $connection->ipaddr . '">' ?>
            <?= $connection->name ?></a>

            <div class="file-item-actions">
                <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="file-item-dropdown-menu" href="javascript:void(0)">Переименовать</a>
                    <a class="file-item-dropdown-menu" href="javascript:void(0)">Удалить</a>
                </div>
            </div>
        </div>
    <?php
}
?>


</div>
