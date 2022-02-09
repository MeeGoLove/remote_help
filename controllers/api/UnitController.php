<?php

namespace app\controllers\api;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;

class UnitController extends ActiveController
{
    public $modelClass = 'app\models\Units';
    /**
     * List of allowed domains.
     * Note: Restriction works only for AJAX (using CORS, is not secure).
     *
     * @return array List of domains, that can access to this API
     */
    public static function allowedDomains()
    {
        return [
            // '*',                        // star allows all domains
            'http://localhost',
            'http://test2.example.com',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            // For cross-domain AJAX request
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    'Origin' => ['*'],
                    'Access-Control-Allow-Origin' => 'http://localhost',
                    'Access-Control-Request-Method'    => ['POST', 'GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Allow-Headers' => ['Origin', 'X-Requested-With', 'Content-Type', 'accept', 'Authorization'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Max-Age'           => 3600, // Cache (seconds)
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Total-Count', 'X-Pagination-Page-Count', 'X-Pagination-Current-Page', 'X-Pagination-Per-Page']

                ],
            ],

        ]);
    }
    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = function ($action) {
            $queryfunc = [$this->modelClass, 'find'];
            return new ActiveDataProvider([
                'query' => $queryfunc(),
                'pagination' => false,
            ]);
        };
        return $actions;
    }
}
