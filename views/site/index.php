<?php

/* @var $this yii\web\View */

$this->title = 'Главная';
?>
<div class="site-index">

    <div class="jumbotron">
        <h2>Добро пожаловать!</h2>

        <p class="lead">Вы находитесь на главной странице Remote Help Address Book, аналога mRemoteNG!</p>

        <p><a class="btn btn-lg btn-success" href="/tree/index">Го</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h2>Статистика</h2>
                <p>Всего подключений: <?=$connectionsCount?> </p>
                <p>Всего подключений с уникальным IP-адресом: </p>                 
                <p>Протоколов подключений: <?=$connectionsTypesCount?></p>
                <p>Типов устройств: <?=$deviceTypesCount?></p>
                <p>Папок/подразделений: <?=$unitsCount?> </p>

                <p>Осуществлено подключений сегодня: </p>
                <p>Осуществлено подключений вчера: </p>
                <p>Осуществлено подключений за последнюю неделю: </p>
                <p>Осуществлено подключений за последние 30 дней: </p>
                <p>Осуществлено подключений за все время </p>
            </div>

           <!-- <div class="col-lg-4">
                <h2>Смешная статистика</h2>
                
                <p><a class="btn btn-default" href="">Бесполезная кнопка &raquo;</a></p>
            </div>-->

            <div class="col-lg-6">
                <h2>Топ 5 подключений за последние 30 дней</h2>
                <p>1 </p>
                <p>2 </p>
                <p>3 </p>
                <p>4 </p>
                <p>5 </p>
                <p><a class="btn btn-default" href="">Бесполезная кнопка &raquo;</a></p>
            </div>

            <div class="col-lg-6">
                <h2>Топ 5 подключений за все время</h2>
                <p>1 </p>
                <p>2 </p>
                <p>3 </p>
                <p>4 </p>
                <p>5 </p>
                <p><a class="btn btn-default" href="">Бесполезная кнопка &raquo;</a></p>
            </div>

        </div>

    </div>
</div>
