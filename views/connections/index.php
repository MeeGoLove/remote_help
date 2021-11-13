<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\icons\Icon;
use app\models\Connections;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подключения';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="connections-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Создать подключение', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'ipaddr',
            'comment',
            //'unit_id',
            [
                'attribute' => 'device_type_id',
                'value' => function ($model, $key, $index, $widget) {
                    return $model->deviceType->name;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'unit_id',
                'value' => function ($model, $key, $index, $widget) {
                    return $model->unit->name;
                },
                'format' => 'raw',
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>
</div>
