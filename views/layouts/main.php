<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>

<body>
    <?php $this->beginBody() ?>

    <div class="custom-container">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
            'innerContainerOptions' => ['class' => 'container-fluid'],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Главная', 'url' => ['/site/index']],
                ['label' => 'Адресная книга', 'url' => ['/tree/index']],
                //['label' => 'Тест', 'url' => ['/tree/check']],               
                ['label' => 'Справочники',  'items' =>
                [
                    ['label' => 'Удаленные протоколы', 'url' => ['/connection-types/index']],
                    ['label' => 'Типы устройств', 'url' => ['/device-types/index']],  
                    ['label' => 'Подключения', 'url' => ['/connections/index']],
                    ['label' => 'Подразделения', 'url' => ['/units/index']],
                ]],
                ['label' => 'Генератор helppack', 'url' => ['/help-pack/index']],
                ['label' => 'Импорт',  'items' =>
                [
                    ['label' => 'Из Radmin', 'url' => ['/import/radmin']],
                    ['label' => 'Из LiteManager', 'url' => ['/import/lm']],
                    ['label' => 'Из TS Gateway', 'url' => ['/import/msrdpgtw']],
                    ['label' => 'Из TS LocalSessionManager', 'url' => ['/import/msrdplsm']],
                    ['label' => 'Из выданных терминалок', 'url' => ['/import/excel']],

                ]],
                ['label' => 'Экспорт',  'items' =>
                [
                    ['label' => 'В Radmin', 'url' => ['/export/radmin']],
                    ['label' => 'В LiteManager', 'url' => ['/export/lm']],                    
                    ['label' => 'В Excel', 'url' => ['/export/excel']],

                ]],

                Yii::$app->user->isGuest ? (['label' => 'Login', 'url' => ['/site/login']]
                ) : ('<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container-fluid">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <!--<footer class="footer">
        <div class="container-fuid">
            <p class="pull-left">&copy; ГАУЗ "ООКБ им. В.И. Войнова" <?= date('Y') ?></p>

            <p class="pull-right"><!= Yii::powered() ?></p>
        </div>
    </footer>-->

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
