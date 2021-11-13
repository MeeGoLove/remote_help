<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Connections */

$this->title = 'Create Connections';
$this->params['breadcrumbs'][] = ['label' => 'Connections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="connections-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
