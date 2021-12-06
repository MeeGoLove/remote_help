<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace app\components\connectionswidget;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * Description of ConnectionsWidget
 *
 * @author Сергей
 */
class ConnectionsGridWidget extends Widget {

    public $connections;
    public $child_units;
    public $parent_id;      

    public function init() {
        parent::init();
        if ($this->connections === null or $this->child_units === null) {
            throw new InvalidConfigException('The "connections" property must be set.');
        }
    }

    public function run() {
        $this->registerAssets(); //выносим регистрацию стилей в отдельный метод
        $view = $this->getView();
        return $this->render('index', ['connections' => $this->connections,
            'child_units' => $this->child_units,
            'parent_id' => $this->parent_id]);
    }

    /**
     * Register assets.
     */
    protected function registerAssets() {
        $view = $this->getView(); // получаем объект вида, в который рендерится виджет
        ConnectionsGridAsset::register($view); // регестрируем файл с классом наборов css, js.
    }
    
    
}
