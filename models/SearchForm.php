<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

namespace app\models;

use yii\base\Model;

class SearchForm extends Model {

    public $keyword;
    public $byipsearch;
    public $onlyNames;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [['keyword'], 'string', 'length' => [2]],
            [['keyword'], 'required'],
            ['byipsearch', 'boolean'],
            ['onlyNames', 'boolean'],
        ];
    }

    /**
     * 
     * @return type
     */
    public function attributeLabels() {
        return [
            'keyword' => 'Ключевое слово',
            'byipsearch' => 'Точный поиск IP',
            'onlyNames' => 'Поиск только по имени'
        ];
    }

}
