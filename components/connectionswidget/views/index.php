<div class="file-manager-container file-manager-col-view col-md-8">
    <div class="file-item">
            <div class="file-item-icon file-item-level-up fas fa-level-up-alt text-secondary"></div>
            <a href="javascript:void(0)" class="file-item-name">
                ..
            </a>
        </div>
    <?php
    foreach ($connections as $connection) {
        ?>
        <div class="file-item">
            <div class="file-item-select-bg bg-primary"></div>


            <?php
            if ($connection->deviceType->optionalConnectionType === null) {                
                echo '<a href="' . $connection->deviceType->defaultConnectionType->protocol_link . $connection->ipaddr . '">
<div class="file-item-img" style="background-image: url('.'/icons-remote/no.jpg);"></div>			
<div class="file-item-img" style="background-image: url('.'/icons-remote/thumb/'.$connection->deviceType->defaultConnectionType->icon .');"></div>
			</a>';               
                //echo '<img class="img-responsive" src="/icons-remote/no.jpg"></img>';
            } else {
                echo '<a href="' . $connection->deviceType->defaultConnectionType->protocol_link . $connection->ipaddr . '">
			<div class="file-item-img" style="background-image: url('.'/icons-remote/thumb/'.$connection->deviceType->defaultConnectionType->icon .');"></div>
			</a>'; 
                 echo '<a href="' . $connection->deviceType->optionalConnectionType->protocol_link . $connection->ipaddr . '">
			<div class="file-item-img" style="background-image: url('.'/icons-remote/thumb/'.$connection->deviceType->optionalConnectionType->icon .');"></div>
			</a>'; 
               
            }
            ?>
            <hr class="m-0" />
                <?= '<a href="' . $connection->deviceType->defaultConnectionType->protocol_link . $connection->ipaddr . '">'?>
                <?= $connection->name ?></a>
    
                        <div class="file-item-actions">
                            <button type="button" class="btn btn-default btn-sm rounded-pill icon-btn borderless md-btn-flat hide-arrow dropdown-toggle" style=" opacity: 0.7;" data-toggle="dropdown"><i class="ion ion-ios-more"></i></button>
                <div class="dropdown-menu dropdown-menu-right" style="min-width: 120px;">
                    <a class="file-item-dropdown-menu" style="font-size: 13px; margin-left: 10px" href="javascript:void(0)">Переименовать</a>
                    <a class="file-item-dropdown-menu" style="font-size: 13px; margin-left: 10px" href="javascript:void(0)">Удалить</a>
                </div>
            </div>
</div>
        <?php
    }
    ?>

    
</div>
