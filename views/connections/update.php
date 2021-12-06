<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Connections */

$this->title = 'Редактирование подключения: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Подключения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="connections-update">
  

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
