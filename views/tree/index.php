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
<div class="tree-index ">

    
    
    
    
    <div class="container-m-nx container-m-ny bg-lightest mb-3">
        <ol class="breadcrumb text-big container-p-x py-3 m-0">
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="javascript:void(0)">projects</a>
            </li>
            <li class="breadcrumb-item active">site</li>
        </ol>

        <hr class="m-0" />



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

                           

                    <?php Pjax::begin(); ?>  

                    <div class="col-md-4">
                        
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

                    <?= ConnectionsGridWidget::widget(['connections' => $connections]); ?>                          
                
                </div>

                <?php Pjax::end(); ?> 


            </div>
        </div>
    </div>
