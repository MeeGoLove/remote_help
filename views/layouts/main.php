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

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Главная', 'url' => ['/site/index']],
                ['label' => 'Адресная книга', 'url' => ['/tree']],
                //['label' => 'Тест', 'url' => ['/tree/check']],
                ['label' => 'Подразделения', 'url' => ['/units']],
                ['label' => 'Подключения', 'url' => ['/connections']],
                ['label' => 'Справочники',  'items' =>
                [
                    ['label' => 'Типы устройств', 'url' => ['/device-types']],
                    ['label' => 'Удаленные протоколы', 'url' => ['/connection-types']]
                ]],
                
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

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; ГАУЗ "ООКБ" <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
