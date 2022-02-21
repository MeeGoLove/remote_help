<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConnectionTypes */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Удаленные протоколы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="connection-types-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить этот удаленный протокол?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
        ],
    ]) ?>

</div>
