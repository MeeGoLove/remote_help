<?php

namespace app\controllers;

use Yii;
use app\models\Connections;
use app\models\Units;
use app\models\SearchForm;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/* * *ext** */
use yii\data\ActiveDataProvider;

/**
 * TreeController implements the CRUD actions for Tree model.
 */
class TreeController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex($unit_id = 0) {
        
        $model_search = new SearchForm();
        $query = Units::find()->orderBy(['name' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 0, // ALL results, no pagination
            ],
        ]);

        if ($model_search->load(Yii::$app->request->post()) && $model_search->validate()) {
            //return var_dump($model_search->keyword);
            if (Yii::$app->request->post()['search-button'] == "btn-name") {
                $connections = Connections::connectionsBySearch($model_search->keyword, true)->with('DeviceTypes');
                $child_units = Units::unitsBySearch($model_search->keyword);
            } else {
                $connections = Connections::connectionsBySearch($model_search->keyword, false);
                $child_units = Units::unitsBySearch($model_search->keyword);
                ;
            }
            /* $connections = Connections::connectionsBySearch($model_search->keyword);
              $child_units = Units::unitsBySearch($model_search->keyword); */
            $unit = Units::findOne(['id' => $unit_id]);
            if ($unit !== null) {
                $parent_id = $unit->parent_id;
                $unit_name = $unit->name;
            } else {
                $parent_id = null;
                $unit = Units::findOne(['parent_id' => null]);
                if ($unit !== null) {
                    $unit_id = $unit->id;
                    return $this->redirect(Url::to(['tree/index', 'unit_id' => $unit_id]));
                } else {
                    return $this->render('index', [
                                'dataProvider' => $dataProvider,
                                'model_search' => $model_search,
                                'connections' => $connections,
                                'child_units' => $child_units,
                                'parent_id' => $parent_id,
                                'unit_name' => 'Корневой элемент не найден!',
                                'unit_id_' => null,
                    ]);
                }
            }

            return $this->render('index', [
                        'dataProvider' => $dataProvider,
                        'model_search' => $model_search,
                        'connections' => $connections,
                        'child_units' => $child_units,
                        'parent_id' => $parent_id,
                        'unit_name' => 'Результаты поиска по строке: ' . $model_search->keyword,
                        'unit_id_' => $unit_id,
            ]);
        }

        /* else {
          return $this->render('index', [
          'dataProvider' => $dataProvider,
          'model_search' => $model_search,
          'connections' => null,
          'child_units' => $child_units,
          'parent_id' => $parent_id,
          'unit_name' => 'Результаты поиска по строке: ' . $model_search->keyword ,
          'unit_id_' => $unit_id,
          ]);
          } */



        $connections = Connections::connectionsByUnitId($unit_id);
        $child_units = Units::childUnitsByUnitId($unit_id);
        $unit = Units::findOne(['id' => $unit_id]);

        if ($unit !== null) {
            $parent_id = $unit->parent_id;
            $unit_name = $unit->name;
        } else {
            $parent_id = null;
            $unit = Units::findOne(['parent_id' => null]);
            if ($unit !== null) {
                $unit_id = $unit->id;
                return $this->redirect(Url::to(['tree/index', 'unit_id' => $unit_id]));
            } else {
                return $this->render('index', [
                            'dataProvider' => $dataProvider,
                            'model_search' => $model_search,
                            'connections' => $connections,
                            'child_units' => $child_units,
                            'parent_id' => $parent_id,
                            'unit_name' => 'Корневой элемент не найден!',
                            'unit_id_' => null,
                ]);
            }
        }
        if (Yii::$app->request->getHeaders()->has('X-PJAX')) {
            return $this->renderAjax('index', [
                        'dataProvider' => $dataProvider,
                        'model_search' => $model_search,
                        'connections' => $connections,
                        'child_units' => $child_units,
                        'parent_id' => $parent_id,
                        'unit_name' => $unit_name,
                        'unit_id_' => $unit_id,
            ]);
        } else {
            return $this->render('index', [
                        'dataProvider' => $dataProvider,
                        'model_search' => $model_search,
                        'connections' => $connections,
                        'child_units' => $child_units,
                        'parent_id' => $parent_id,
                        'unit_name' => $unit_name,
                        'unit_id_' => $unit_id,
            ]);
        }
    }

    /**
     * Displays a single Tree model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tree model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Units();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionAdd() {
        $model = new Units();
        $model->loadDefaultValues();
        $id = Yii::$app->request->get('id');
        $model->parent_id = $id;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => true];
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('add', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('add', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tree model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        /*
          if ($model->load(Yii::$app->request->post()) && $model->save()) {
          return $this->redirect(['view', 'id' => $model->id]);
          } else {
          return $this->render('update', [
          'model' => $model,
          ]);
          } */

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => true];
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tree model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $from_tree = 0, $unit_id = 0) {
        $this->findModel($id)->delete();
        if ($from_tree == 1)
            return $this->redirect(['/tree/index?unit_id=' . $unit_id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Tree model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tree the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Units::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Tree model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tree the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelConnection($id) {
        if (($model = Connections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
