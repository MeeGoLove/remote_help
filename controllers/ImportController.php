<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Connections;
use app\models\Units;
use app\models\UploadForm;
use yii\web\UploadedFile;

class ImportController extends Controller
{

    public function actionLm()
    {
        return $this->render('lm');
    }

    public function actionMsrdplsm()
    {
        $model = new UploadForm();
        if (
            Yii::$app->request->isPost && $model->load(Yii::$app->request->post())
            //&& $model->validate()
        ) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            if ($model->upload()) {
                if ($model->clearDir)
                {
                    Units::deleteAll(['parent_id'=> $model->rootUnitId]);
                    Connections::deleteAll(['unit_id' => $model->rootUnitId]);
                }
                $message = UploadForm::importMsRdpLSM($model->rootUnitId, $model->deviceTypeId);
                Yii::$app->session->setFlash('success', $message);
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось загрузить файл!');
            }
        }
        return $this->render('msrdplsm', ['model' => $model]);

    }

    public function actionMsrdpgtw()
    {
        return $this->render('msrdpgtw');
    }

    public function actionRadmin()
    {   $model = new UploadForm();
        if (
            Yii::$app->request->isPost && $model->load(Yii::$app->request->post())
            //&& $model->validate()
        ) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            if ($model->upload()) {
                $message = UploadForm::importRadmin($model->rootUnitId, $model->deviceTypeId);
                Yii::$app->session->setFlash('success', $message);
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось загрузить файл!');
            }
        }
        return $this->render('radmin', ['model' => $model]);
    }
}
