<?php

namespace app\controllers;

use app\models\Connections;
use app\models\ConnectionStats;
use app\models\ConnectionTypes;
use app\models\ContactForm;
use app\models\DeviceTypes;
use app\models\LoginForm;
use app\models\Units;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $connectionsCount = Connections::find()->count();
        $IpAddressCount = Connections::find()->select('ipaddr')->distinct()->count();
        $unitsCount = Units::find()->count();
        $deviceTypesCount = DeviceTypes::find()->count();
        $connectionsTypesCount = ConnectionTypes::find()->count();
        $countAllTime = Connections::find()->sum('count_connect');


        //Подсчет необходимого времени в Unix-формате, для отражения сттистики по дням
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $beginYesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        $Last7Days = mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
        $Last30Days = mktime(0, 0, 0, date('m'), date('d') - 30, date('Y'));


        $countToday = ConnectionStats::find()->where('connection_date' . '>=' . $beginToday)->count();
        $countYesterDay = ConnectionStats::find()->where(['and',
            'connection_date' . '<=' . $beginToday,
            'connection_date' . '>=' . $beginYesterday,
        ])->count();
        $countWeek = ConnectionStats::find()->where('connection_date' . '>=' . $Last7Days)->count();
        $countMonth = ConnectionStats::find()->where('connection_date' . '>=' . $Last30Days)->count();

        $top7DaysConnections = ConnectionStats::find()->
        select(['connection_id, count(*) as counters'])->where('connection_date' . '>=' . $Last7Days)->groupBy('connection_id')
            ->orderBy('counters DESC')
            ->limit(5)
            ->all();

        $top30DaysConnections = ConnectionStats::find()->
        select(['connection_id, count(*) as counters'])->where('connection_date' . '>=' . $Last30Days)->groupBy('connection_id')
            ->orderBy('counters DESC')
            ->limit(5)
            ->all();
        $topAllTimeConnections = ConnectionStats::find()->
        select(['connection_id, count(*) as counters'])->groupBy('connection_id')
            ->orderBy('counters DESC')
            ->limit(5)
            ->all();

        return $this->render('index', ['connectionsCount' => $connectionsCount,
            'IpAddressCount' => $IpAddressCount,
            'unitsCount' => $unitsCount,
            'deviceTypesCount' => $deviceTypesCount,
            'connectionsTypesCount' => $connectionsTypesCount,
            'countAllTime' => $countAllTime,
            'countToday' => $countToday,
            'countYesterDay' => $countYesterDay,
            'countWeek' => $countWeek,
            'countMonth' => $countMonth,
            'top7DaysConnections' => $top7DaysConnections,
            'top30DaysConnections' => $top30DaysConnections,
            'topAllTimeConnections' => $topAllTimeConnections,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
