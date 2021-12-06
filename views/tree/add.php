<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tree */

$this->title = 'Создать подразделение';
$this->params['breadcrumbs'][] = ['label' => 'Trees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tree-add">



    <?=
    $this->render('_form_add', [
        'model' => $model,
    ])
    ?>

</div>