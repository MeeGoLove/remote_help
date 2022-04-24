<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Connections;
use app\models\Units;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

class ImportController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionExcel()
    {
        $model = new UploadForm();
        if (
            Yii::$app->request->isPost && $model->load(Yii::$app->request->post())
        ) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            if ($model->upload()) {
                $message = UploadForm::importExcel($model->rootUnitId, $model->deviceTypeId);
                Yii::$app->session->setFlash('success', $message);
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось загрузить файл!');
            }
        }
        return $this->render('excel', ['model' => $model]);
    }

    public function actionLm()
    {
        return $this->render('lm');
    }

    public function actionMsrdplsm()
    {
        $model = new UploadForm();
        if (
            Yii::$app->request->isPost && $model->load(Yii::$app->request->post())
        ) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            if ($model->upload()) {
                if ($model->clearDir) {
                    Units::deleteAll(['parent_id' => $model->rootUnitId]);
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
        $model = new UploadForm();
        if (
            Yii::$app->request->isPost && $model->load(Yii::$app->request->post())

        ) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            if ($model->upload()) {
                if ($model->clearDir) {
                    Units::deleteAll(['parent_id' => $model->rootUnitId]);
                    Connections::deleteAll(['unit_id' => $model->rootUnitId]);
                }
                $message = UploadForm::importMsRdpGateway($model->rootUnitId, $model->deviceTypeId);
                Yii::$app->session->setFlash('success', $message);
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось загрузить файл!');
            }
        }
        return $this->render('msrdpgtw', ['model' => $model]);
    }

    public function actionRadmin()
    {
        $model = new UploadForm();
        if (
            Yii::$app->request->isPost && $model->load(Yii::$app->request->post())
        ) {
            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            if ($model->upload()) {
                if ($model->clearDir) {
                    Units::deleteAll(['parent_id' => $model->rootUnitId]);
                    Connections::deleteAll(['unit_id' => $model->rootUnitId]);
                }
                $message = UploadForm::importRadmin($model->rootUnitId, $model->deviceTypeId);
                Yii::$app->session->setFlash('success', $message);
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось загрузить файл!');
            }
        }
        return $this->render('radmin', ['model' => $model]);
    }
}
