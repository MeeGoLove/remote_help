<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConnectionTypes */

$this->title = 'Create Connection Types';
$this->params['breadcrumbs'][] = ['label' => 'Connection Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="connection-types-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
