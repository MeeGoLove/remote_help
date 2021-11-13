<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConnectionTypes */

$this->title = 'Update Connection Types: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Connection Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="connection-types-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
