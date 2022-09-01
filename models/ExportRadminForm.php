<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Connections;
use app\models\Units;
use Exception;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user This property is read-only.
 *
 */
class ExportRadminForm extends Model
{
    public $deviceTypeId;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['deviceTypeId', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return
            ['deviceTypeId' => 'Тип устройств, которые будут экспортированы'];
    }

    public static function ExportRadminCSV($deviceTypeId)
    {
        $allUnits = Units::find()->all();
        //$units = Units::find([$allUnits-Connections->DeviceTypes->id =>$deviceTypeId]);
        $connections = Connections::find(['device_type_id' => $deviceTypeId])->all();
        $startLine = array(
            'Частота', 'ВидЭкрана', 'ГлубинаЦвета', 'СпецСочетания', 'Курсор',
            'КачествоЗвука', 'ГолосовойЧатИмя', 'ГолосовойЧатИнфо', 'ТекстовыйЧатИмя', 'ТекстовыйЧатИнфо',
            'Адрес', 'ИмяЗаписи', 'Порт', 'Логин', 'Домен', 'ИмяХоста', 'NoWinLogin', 'ИДЗаписи', 'ИДСервера',
            'ИДРодителя', 'ЭтоГруппа'
        );
        $deviceLine = array('100', '2', '1', '1', '1', '5', 'User', '', 'User', '', 'ipAddr', 'name', '4899', '', '', '', '', 'id', '', 'unitId', '0');

        $unitLine = array('', '', '', '', '', '', '', '', '', '', '', 'name', '', '', '', '', '', 'id', '', '', '1');
        $fp = fopen('file-export.csv', 'w');

        fputcsv($fp, $startLine, ';');
        $maxId = 0;
        foreach ($allUnits as $unit) {
            $unitLine = array('', '', '', '', '', '', '', '', '', '', '', $unit->name, '', '', '', '', '', $unit->id, '', '', '1');
            fputcsv($fp, $unitLine, ';');
            if ($maxId <= $unit->id) {
                $maxId = $unit->id;
            }
        }
        $maxId = $maxId + 100;
        foreach ($connections as $connection) {
            $deviceLine = array('100', '2', '1', '1', '1', '5', 'User', '', 'User', '', $connection->ipaddr, $connection->name, '4899', '', '', '', '', 'id', '', $connection->unit_id, '0');
            fputcsv($fp, $deviceLine, ';');
            $maxId++;
        }
        fclose($fp);
        $text = file_get_contents('file-export.csv');
        $text = mb_convert_encoding($text, 'windows-1251', 'utf-8');
        file_put_contents('file-export.csv', $text);
    }
}
