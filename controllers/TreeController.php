<?php

namespace app\controllers;

use app\models\Connections;
use app\models\ConnectionStats;
use app\models\ConnectionTypes;
use app\models\SearchForm;
use app\models\Units;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/* * *ext** */

/**
 * TreeController implements the CRUD actions for Tree model.
 */
class TreeController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
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

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex(
        $unit_id = 0,
        $view_type = "grid",
        $change_view = false,
        $editing = false,
        $changeEditing = false,
        $admin = false,
        $changeAdmin = false,
        $strictedIP = false,
        $onlyNames = true
    )
    {
        //Через куки проверяем в каком виде будет отражаться папки и подключения
        //В виде списка или иконок
        //Получаем куки
        $cookies = Yii::$app->request->cookies;
        //Если нет куки, то мы её создадим
        if (!$cookies->has('view_type')) {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'view_type',
                'value' => $view_type
            ]));
        } else {
            //Если нажимали переключатель, то сохраним в куке вид
            if ($change_view) {
                Yii::$app->response->cookies->remove('view_type');
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'view_type',
                    'value' => $view_type
                ]));
            } //в любом другом случае, просто прочтем из куки предпочтительный вид
            else {
                $view_type = $cookies->get('view_type');
            }
        }

        //Аналогично с кнопкой редактирвоание
        if (!$cookies->has('editing')) {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'editing',
                'value' => $editing
            ]));
        } else {
            //Если нажимали переключатель, то сохраним в куке вид
            if ($changeEditing) {
                Yii::$app->response->cookies->remove('editing');
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'editing',
                    'value' => $editing
                ]));
            } //в любом другом случае, просто прочтем из куки предпочтительный вид
            else {
                $editing = $cookies->get('editing');
            }
        }


                //Аналогично с кнопкой редактирвоание
                if (!$cookies->has('strictedIP')) {
                    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'strictedIP',
                        'value' => $strictedIP
                    ]));
                } else {
                    //Если нажимали переключатель, то сохраним в куке вид                    
                        $strictedIP = $cookies->get('strictedIP');                    
                }

                //Аналогично с кнопкой редактирвоание
                                if (!$cookies->has('onlyNames')) {
                                    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                                        'name' => 'onlyNames',
                                        'value' => $onlyNames
                                    ]));
                                } else {
                                    //Если нажимали переключатель, то сохраним в куке вид                    
                                        $onlyNames = $cookies->get('onlyNames');                    
                                }

        //Аналогично с режимом администратора
        if (!$cookies->has('admin')) {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'admin',
                'value' => $admin
            ]));
        } else {
            //Если нажимали переключатель, то сохраним в куке вид
            if ($changeAdmin) {
                Yii::$app->response->cookies->remove('admin');
                Yii::$app->response->cookies->add(new \yii\web\Cookie([
                    'name' => 'admin',
                    'value' => $admin
                ]));
            } //в любом другом случае, просто прочтем из куки предпочтительный вид
            else {
                $admin = $cookies->get('admin');
            }
        }

        if ($admin == "1") {
            $admin = true;
        } else {
            $admin = false;
        }


        $defaultView = 'index-admin';

        if ($admin) {
            $defaultView = 'index-admin';
        }


        //VSCode при автоформатировании превращает (boolean)$editing в (bool)$editing, поэтому такой костыль
        if ($editing == "1") {
            $editing = true;
        } else {
            $editing = false;
        }


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
            Yii::$app->response->cookies->remove('strictedIP');
                        Yii::$app->response->cookies->add(new \yii\web\Cookie([
                            'name' => 'strictedIP',
                            'value' => $model_search->byipsearch
                        ]));
                        $strictedIP = $model_search->byipsearch;
                        if ($strictedIP == "1") {
                            $strictedIP = true;
                        } else {
                            $strictedIP = false;
                        }
                Yii::$app->response->cookies->remove('onlyNames');
                        Yii::$app->response->cookies->add(new \yii\web\Cookie([
                            'name' => 'onlyNames',
                            'value' => $model_search->onlyNames
                        ]));
                        $onlyNames = $model_search->onlyNames;
                        if ($onlyNames == "1") {
                            $onlyNames = true;
                        } else {
                            $onlyNames = false;
                        }
           /* if (!$model_search->byipsearch) {
                $connections = Connections::connectionsBySearch($model_search->keyword, true);
                $child_units = Units::unitsBySearch($model_search->keyword);
            } else {
                $connections = Connections::connectionsBySearch($model_search->keyword, false);
                $child_units = Units::unitsBySearch($model_search->keyword);;
            }
*/
            $connections = Connections::connectionsBySearch($model_search->keyword, $strictedIP, $onlyNames);
            $child_units = Units::unitsBySearch($model_search->keyword);
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
                    return $this->render($defaultView, [
                        'dataProvider' => $dataProvider,
                        'model_search' => $model_search,
                        'connections' => $connections,
                        'child_units' => $child_units,
                        'parent_id' => $parent_id,
                        'unit_name' => 'Корневой элемент не найден!',
                        'unit_id_' => null,
                        'view_type' => $view_type,
                        'editing' => $editing,
                        'admin' => $admin,
                        'strictedIP' =>  $strictedIP,
                        'onlyNames' => $onlyNames
                    ]);
                }
            }

            return $this->render($defaultView, [
                'dataProvider' => $dataProvider,
                'model_search' => $model_search,
                'connections' => $connections,
                'child_units' => $child_units,
                'parent_id' => $parent_id,
                'unit_name' => 'Результаты поиска по строке: ' . $model_search->keyword,
                'unit_id_' => $unit_id,
                'view_type' => $view_type,
                'editing' => $editing,
                'admin' => $admin,
                'strictedIP' =>  $strictedIP,
                'onlyNames' => $onlyNames
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
        if ($strictedIP == "1") {
            $strictedIP = true;
        } else {
            $strictedIP = false;
        }
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
                return $this->render($defaultView, [
                    'dataProvider' => $dataProvider,
                    'model_search' => $model_search,
                    'connections' => $connections,
                    'child_units' => $child_units,
                    'parent_id' => $parent_id,
                    'unit_name' => 'Корневой элемент не найден!',
                    'unit_id_' => null,
                    'view_type' => $view_type,
                    'editing' => $editing,
                    'admin' => $admin,
                    'strictedIP' =>  $strictedIP,
                    'onlyNames' => $onlyNames
                ]);
            }
        }
        if (Yii::$app->request->getHeaders()->has('X-PJAX')) {
            return $this->renderAjax($defaultView, [
                'dataProvider' => $dataProvider,
                'model_search' => $model_search,
                'connections' => $connections,
                'child_units' => $child_units,
                'parent_id' => $parent_id,
                'unit_name' => $unit_name,
                'unit_id_' => $unit_id,
                'view_type' => $view_type,
                'editing' => $editing,
                'admin' => $admin,
                'strictedIP' =>  $strictedIP,
                'onlyNames' => $onlyNames
            ]);
        } else {
            return $this->render($defaultView, [
                'dataProvider' => $dataProvider,
                'model_search' => $model_search,
                'connections' => $connections,
                'child_units' => $child_units,
                'parent_id' => $parent_id,
                'unit_name' => $unit_name,
                'unit_id_' => $unit_id,
                'view_type' => $view_type,
                'editing' => $editing,
                'admin' => $admin,
                'strictedIP' =>  $strictedIP,
                'onlyNames' => $onlyNames
            ]);
        }
    }

    /**
     * Displays a single Tree model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tree model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Units();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionAdd()
    {
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

    public function actionCheck()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) { /* текущий запрос является AJAX запросом */
            set_time_limit(0);
            $link = $request->post('link');
            $response = [];
            /*foreach ($links as $link)
            {*/
            list($proto, $ip) = explode("://", $link);
            $connectionType = ConnectionTypes::findOne(['protocol_link' => $proto . '://']);
            if (!empty($connectionType)) {
                $ports = explode(',', $connectionType->port);
                $test_con = false;
                foreach ($ports as $port)
                    $test_con = $test_con || TreeController::checkPort($ip, $port);
            } else {
                $test_con = false;
            }
            //array_push($response, ['link' => $link, 'checkResult' => $test_con]);
            /*}*/
            //return var_dump($links);

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['checkResult' => $test_con];
            //return $test_con;
            //return $response;
        }
        return $this->render('check', ['result' => 'ok']);
    }


    public function actionStats()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) { /* текущий запрос является AJAX запросом */
            $connectionId = $request->post('connectionId');
            $connection = Connections::findOne($connectionId);
            $connection->count_connect++;
            //$connection->count_connect++;
            $connection->save();
            $connectionStats = new ConnectionStats();
            $connectionStats->connection_id = $connectionId;
            $connectionStats->connection_date = time();
            $connectionStats->operator_ip = $request->remoteIP;
            $connectionStats->save();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['checkResult' => 'ok'];
        }

    }

    /**
     * Updates an existing Tree model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
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
    public function actionDelete($id, $from_tree = 0, $unit_id = 0)
    {
        if ($id == $unit_id) {
            $unit_id = Units::findOne($unit_id)->parent_id;
        }
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
    protected function findModel($id)
    {
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
    protected function findModelConnection($id)
    {
        if (($model = Connections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public static function checkPort($targetIP, $portNumber)
    {
        //stream_set_timeout(1);
        if (!$socket = @fsockopen($targetIP, $portNumber, $errno, $errstr, 1)) {
            return false;
        } else {
            fclose($socket);
            return true;
        }
    }

    public function actionCheckDns()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) { /* текущий запрос является AJAX запросом */
            set_time_limit(0);
            $link = $request->post('link');

            $link = explode("  \n", $link );
            $ipAddr = $link[0];
            $name = gethostbyaddr($ipAddr);
            if ($name == $ipAddr)
            {
                $name = "NOTFOUNDBYIP";
            }
            else {
                $name = strtok($name, ".");
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['checkResult' => $name];
            //return ['checkResult' => $linkE[0]];
        }
    }
}
