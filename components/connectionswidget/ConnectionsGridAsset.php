<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace app\components\connectionswidget;

use yii\web\AssetBundle;

/**
 * Description of ConnectionsGridAsset
 *
 * @author Сергей
 */
class ConnectionsGridAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/style.css',
        '/css/connectionswidget/style.css',
        '/css/connectionswidget/ionicons.min.css',
        '/css/connectionswidget/font-awesome.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];
    public function init()
    {
        
        parent::init();
    }

}
