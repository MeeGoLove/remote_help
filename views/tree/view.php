<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Units */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Адресная', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tree-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить ' . $model->name . '?' ,
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
           // ['attribute' => 'parent_id', 'value' => $model->parent->name]
        ],
    ]) ?>

</div>