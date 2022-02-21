<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Удаленные протоколы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="connection-types-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать новый удаленный протокол', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'protocol_link',
            'protocol_link_readonly',
            'port',
            [
                'attribute' => 'icon',
                'value' => function ($model) {
                    if (!empty($model->icon)) {
                        return Html::img('/icons-remote/thumb/' . $model->icon, ['style' => 'height:25px;']);
                    }
                },
                'format' => 'raw',

            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
