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

$this->title = 'Trees';
?>
<div class="tree-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

    <p>
        <?php
        if ($dataProvider->count == 0)
            echo Html::a('Создать корневой элемент', ['add'], ['class' => 'btn btn-success']);
        $dataProvide = new ActiveDataProvider(['query' => \app\models\Units::find()->orderBy(['name' => SORT_ASC])]);
        ?>
    </p>
    <div class="row">
        <div class="col-md-12">            
            <div class="panel panel-default">
                <div class="panel-body pb-filemng-panel-body">

                    <?php Pjax::begin(); ?>  

                    <div class="row">
                        <div class="col-sm-3 col-md-4 pb-filemng-template-treeview">
                            <div class="collapse navbar-collapse" id="treeview-toggle">
                                <!-- блок с tree -->

                                <?=
                                TreeGrid::widget([
                                    'dataProvider' => $dataProvide,
                                    'keyColumnName' => 'id',
                                    'showOnEmpty' => FALSE,
                                    'parentColumnName' => 'parent_id',
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
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>

                        <?= ConnectionsGridWidget::widget(['connections' => $connections]); ?>                          
                    </div>

                    <?php Pjax::end(); ?> 

                </div>
            </div>
        </div>
    </div>
</div>