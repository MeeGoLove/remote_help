<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
use app\components\connectionswidget\ConnectionsGridWidget;
/* * *ext** */
use leandrogehlen\treegrid\TreeGrid;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Адресная книга';
?>
<div class="tree-index ">

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?php
        if ($dataProvider->count == 0)
            echo Html::a('Создать корневой элемент', ['add'], ['class' => 'btn btn-success']);
        //$dataProvider = new ActiveDataProvider(['query' => \app\models\Units::find()->orderBy(['name' => SORT_ASC])]);
        ?>
    </p>
    <div class="row">
        <div class="col-md-12">          



            <?php Pjax::begin(); ?>  

            <div class="col-md-5">
                <!--style="
                 overflow-y: scroll;"-->

                <!-- блок с tree -->

                <?php
                ?><?=
                TreeGrid::widget([
                    'dataProvider' => $dataProvider,
                    'keyColumnName' => 'id',
                    'showOnEmpty' => FALSE,
                    'parentColumnName' => 'parent_id',
                    //способ подсвечивать текущее выбранное подразделение
                    'rowOptions' => function ($model, $key) {
                        $request = Yii::$app->request;
                        $unit_id = $request->get('unit_id');
                        if ($key == $unit_id) {
                            return ['class' => 'info'];
                        }
                    },
                    'columns' =>
                    [
                        //'name',
                        [
                            //'attribute' => 'name',
                            'label' => 'Иерархия',
                            'value' => function (\app\models\Units $data) {
                                return Html::a(Html::encode($data->name), Url::to(['tree/index', 'unit_id' => $data->id]));
                            },
                            'format' => 'raw',
                        ],
                        ['class' => 'yii\grid\ActionColumn',
                            'template' => '{update} {delete} {add}',
                            'buttons' => [
                                'add' => function ($url, $model, $key) {
                                    return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url);
                                },
                            ]
                        ]
                    ]
                ]);
                ?>                       
            </div>

            <?= ConnectionsGridWidget::widget(['connections' => $connections, 'child_units' => $child_units, 'parent_id' => $parent_id,]); ?>                          

        </div>

        <?php Pjax::end();
        ?> 


    </div>
</div>
</div>
