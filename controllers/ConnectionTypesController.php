<?php

namespace app\controllers;

use Yii;
use app\models\ConnectionTypes;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ConnectionTypesController implements the CRUD actions for ConnectionTypes model.
 */
class ConnectionTypesController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ConnectionTypes models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => ConnectionTypes::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConnectionTypes model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ConnectionTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ConnectionTypes();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // загружаем изображение и выполняем resize исходного изображения
            $model->upload = UploadedFile::getInstance($model, 'icon');
            if ($name = $model->uploadIcon()) { // если изображение было загружено
                // сохраняем в БД имя файла изображения
                $model->icon = $name;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ConnectionTypes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        // старое изображение, которое надо удалить, если загружено новое
        $old = $model->icon;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // загружаем изображение и выполняем resize исходного изображения
            $model->upload = UploadedFile::getInstance($model, 'icon');
            if ($new = $model->uploadIcon()) { // если изображение было загружено
                // удаляем старое изображение
                if (!empty($old)) {
                    $model::removeIcon($old);
                }
                // сохраняем в БД новое имя
                $model->icon = $new;
            } else { // оставляем старое изображение
                $model->icon = $old;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ConnectionTypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ConnectionTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConnectionTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ConnectionTypes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
