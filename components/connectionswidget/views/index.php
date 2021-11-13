<div class="col-sm-12 col-md-8 pb-filemng-template-body">
    <?php
    foreach ($connections as $connection) {
        ?>



        <div class="col-xs-6 col-sm-6 col-md-3 pb-filemng-body-folders">
            <a href=<?php echo "\"radmin://" . $connection['ipaddr'] . "\"" ?>><img class="img-responsive" src="/icons-remote/radmin.jpg"></a>
            <a href="vnc://172.30.108.11"><img class="img-responsive" src="/icons-remote/vnc.jpg"></img></a>
            <p class="pb-filemng-paragraphs"><br>

    <?= $connection['name'] ?></p></div>



<!-- блок с иконками -->



                        <!--
                        
                                                    <div class="col-xs-6 col-sm-6 col-md-3 pb-filemng-body-folders">
                                                        <a href="radmin://172.30.108.11"><img class="img-responsive" src="/icons-remote/radmin.jpg"></a>
                                                        <a href="vnc://172.30.108.11"><img class="img-responsive" src="/icons-remote/vnc.jpg"></img></a>
                                                        <p class="pb-filemng-paragraphs"><br>101 кабинет врач</p></div>
                        
                        
                        
                                                    <div class="col-xs-6 col-sm-6 col-md-3 pb-filemng-body-folders">
                                                        <img class="img-responsive" src="/icons-remote/no.jpg"></img>
                                                        <a href="vnc://172.30.108.11"><img class="img-responsive" src="/icons-remote/vnc.jpg"></img></a>	
                                                        <p class="pb-filemng-paragraphs"><br>101 кабинет м/с</p>
                                                        <img class="img-responsive" src="/icons-remote/no.jpg"></img>
                                                    </div>	
                        -->
    <?php
}
?>
</div>
