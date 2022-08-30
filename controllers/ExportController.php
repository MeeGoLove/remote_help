<?php

namespace app\controllers;

class ExportController extends \yii\web\Controller
{
    public function actionExcel()
    {
        return $this->render('excel');
    }

    public function actionLm()
    {
        return $this->render('lm');
    }

    public function actionRadmin()
    {
        return $this->render('radmin');
    }

}
