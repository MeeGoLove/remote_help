<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DeviceTypes */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы устройств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="device-types-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить этот тип подключения?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [            
            'name',
            [
                'attribute' => 'default_connection_type_id',
                'value' => function ($model) {
                    return $model->defaultConnectionType->name;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'optional_connection_type_id',
                'value' => function ($model) {
                    if ($model->optional_connection_type_id != "")
                        return $model->optionalConnectionType->name;
                },
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
