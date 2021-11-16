<div class="col-sm-12 col-md-8 pb-filemng-template-body">
    <?php
    foreach ($connections as $connection) {
        ?>
        <div class="col-xs-6 col-sm-6 col-md-3 pb-filemng-body-folders">


            <?php
            if ($connection->deviceType->optionalConnectionType === null) {
                echo '<img class="img-responsive" src="/icons-remote/no.jpg"></img>';
                echo '<a href="' . $connection->deviceType->defaultConnectionType->protocol_link . $connection->ipaddr . '">
                <img class="img-responsive" src="/icons-remote/thumb/' . $connection->deviceType->defaultConnectionType->icon . '"> </a>';
                //echo '<img class="img-responsive" src="/icons-remote/no.jpg"></img>';
            } else {
                echo '<a href="' . $connection->deviceType->defaultConnectionType->protocol_link . $connection->ipaddr . '">
                <img class="img-responsive" src="/icons-remote/thumb/' . $connection->deviceType->defaultConnectionType->icon . '"> </a>';
                echo '<a href="' . $connection->deviceType->optionalConnectionType->protocol_link . $connection->ipaddr . '">
                <img class="img-responsive" src="/icons-remote/thumb/' . $connection->deviceType->optionalConnectionType->icon . '"> </a>';
            }
            // <a href = "vnc://172.30.108.11"><img class = "img-responsive" src = "/icons-remote/vnc.jpg"></img></a>
            ?>
            <p class="pb-filemng-paragraphs"><br>

                <?= $connection->name ?></p></div>
        <?php
    }
    ?>
</div>
