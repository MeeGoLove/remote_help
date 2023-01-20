<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Connections */

//$this->title = 'Создать новое подключение';
$this->params['breadcrumbs'][] = ['label' => 'Подключения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="connections-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
