<?php

/* @var $this yii\web\View */

$this->title = 'Главная';
?>
<div class="site-index">


    <div class="body-content">

        <div class="row">
            <div class="jumbotron col-lg-9">
                <h2>Добро пожаловать!</h2>

                <p class="lead">Вы находитесь на главной странице Remote Help Address Book, аналога mRemoteNG!</p>

                <p><a class="btn btn-lg btn-success" href="/tree/index">Го</a></p>
            </div>
            <div class="col-lg-3">
                <h2 class="text-center">Сухая и неинтересная статистика</h2>
                <p>Всего подключений: <?= $connectionsCount ?> </p>
                <p>Всего подключений с уникальным IP-адресом: <?= $IpAddressCount ?></p>
                <p>Протоколов подключений: <?= $connectionsTypesCount ?></p>
                <p>Типов устройств: <?= $deviceTypesCount ?></p>
                <p>Папок/подразделений: <?= $unitsCount ?> </p>

                <p>Осуществлено подключений сегодня: <?= $countToday ?></p>
                <p>Осуществлено подключений вчера: <?= $countYesterDay ?> </p>
                <p>Осуществлено подключений за последнюю неделю: <?= $countWeek ?> </p>
                <p>Осуществлено подключений за последние 30 дней: <?= $countMonth ?> </p>
                <p>Осуществлено подключений за все время <?= $countAllTime ?> </p>
            </div>

            <!-- <div class="col-lg-4">
                 <h2>Смешная статистика</h2>

                 <p><a class="btn btn-default" href="">Бесполезная кнопка &raquo;</a></p>
             </div>-->
        </div>
        <div class="row">
            <div class="col-lg-4">
                <h2 class="text-center">Топ 5 подключений за последние 7 дней</h2>
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Имя</th>
                        <th>IP aдрес</th>
                        <th>Папка</th>
                        <th>Количество подключений</th>
                    </tr>
                    <?php
                    foreach ($top7DaysConnections as $top7DaysConnection) {
                        echo '<tr><td>';
                        echo($top7DaysConnection->connections->name);
                        echo '</td><td>';
                        echo($top7DaysConnection->connections->ipaddr);
                        echo '</td><td>';
                        echo($top7DaysConnection->connections->unit->name);
                        echo '</td><td>';
                        echo($top7DaysConnection->counters);
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
                <!--<p><a class="btn btn-default" href="">Бесполезная кнопка &raquo;</a></p>-->
            </div>

            <div class="col-lg-4">
                <h2 class="text-center">Топ 5 подключений за последние 30 дней</h2>
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Имя</th>
                        <th>IP aдрес</th>
                        <th>Папка</th>
                        <th>Количество подключений</th>
                    </tr>
                    <?php
                    foreach ($top30DaysConnections as $top30DaysConnection) {
                        echo '<tr><td>';
                        echo($top30DaysConnection->connections->name);
                        echo '</td><td>';
                        echo($top30DaysConnection->connections->ipaddr);
                        echo '</td><td>';
                        echo($top30DaysConnection->connections->unit->name);
                        echo '</td><td>';
                        echo($top30DaysConnection->counters);
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
                <!--<p><a class="btn btn-default" href="">Бесполезная кнопка &raquo;</a></p>-->
            </div>

            <div class="col-lg-4">
                <h2 class="text-center">Топ 5 подключений за все время</h2>
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Имя</th>
                        <th>IP aдрес</th>
                        <th>Папка</th>
                        <th>Количество подключений</th>
                    </tr>
                    <?php
                    foreach ($topAllTimeConnections as $topAllTimeConnection) {
                        echo '<tr><td>';
                        echo($topAllTimeConnection->connections->name);
                        echo '</td><td>';
                        echo($topAllTimeConnection->connections->ipaddr);
                        echo '</td><td>';
                        echo($topAllTimeConnection->connections->unit->name);
                        echo '</td><td>';
                        echo($topAllTimeConnection->counters);
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
                <!--<p><a class="btn btn-default" href="">Бесполезная кнопка &raquo;</a></p>-->
            </div>
        </div>

    </div>
</div>
