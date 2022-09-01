<?php

namespace app\controllers;

use Yii;
use app\models\ExportRadminForm;
use yii\base\ErrorException;

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
        $model = new ExportRadminForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                ExportRadminForm::ExportRadminCSV($model->deviceTypeId);
                $file = Yii::getAlias('file-export.csv');
            return Yii::$app->response->sendFile($file);

            } catch (ErrorException $e) {
                Yii::warning("При попытке экспорта возникла ошибка: " . $e->getMessage());
            }
        }
        return $this->render('radmin', ['model' => $model]);
    }
}
