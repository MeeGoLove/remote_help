<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы устройств';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-types-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать новый тип устройства', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],            
            'name',
            [
                'attribute' => 'default_connection_type_id',
                'value' => function ($model, $key, $index, $widget) {
                    return $model->defaultConnectionType->name;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'optional_connection_type_id',
                'value' => function ($model, $key, $index, $widget) {
                    if ($model->optional_connection_type_id != "")
                        return $model->optionalConnectionType->name;
                },
                'format' => 'raw',
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>


</div>
