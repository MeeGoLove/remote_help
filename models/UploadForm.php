<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $importFile;
    public $rootUnitId;
    public $deviceTypeId;

    public function rules()
    {
        return [
            [
                ['importFile'], 'file', 'skipOnEmpty' => false,
                //'extensions' => 'xml, csv, rpb'
            ],
            [['rootUnitId', 'deviceTypeId'], 'required']
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->importFile->saveAs('uploads/import.' . $this->importFile->extension);
            return true;
        } else {
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'importFile' => 'Файл CSV для импорта',
            'rootUnitId' => 'Корневая папка, куда будут импортированы подключения',
            'deviceTypeId' => 'Тип устройства для импортируемых подключений',
        ];
    }

    public static function importRadmin($rootUnitId, $deviceTypeId)
    {
        $unit_count = 0;
        $connections_count = 0;
        if (($fp = fopen("uploads/import.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
                $list[] = $data;
            }
            fclose($fp);
            $units_ids = [];
            //Два прохода! В первом проходе добавляем все папки в корень
            //Во втором проходе меняем id родителя папки подключения для папок вне корня и создаем подключения
            //Проход №1
            foreach ($list as $k) {
                //По 13 столбцу определяем с чем мы имеем дело
                //Слово Порт, пропускаем это заголовки столбцов
                if ($k[12] == "Порт") {
                    continue;
                }
                //Пустое значение -> это папка соединения
                if ($k[12] == "") {
                    $unit = new Units();
                    $unit->name = $k[11];
                    $unit->parent_id = $rootUnitId;
                    $unit->save();
                    $unit->id . "<br>";
                    $units_ids[$k[17]] = $unit->id;
                    $unit_count++;
                }
                //Непустое значение -> это какое-либо соединение
                else {
                    //Подключения без родителей в корень
                    if ($k[19] == "") {
                        $connection = new Connections();
                        $connection->device_type_id = $deviceTypeId;
                        $connection->name = $k[11];
                        $connection->ipaddr = $k[10];
                        $connection->unit_id = $rootUnitId;
                        $connection->comment = "Импортировано из Radmin";
                        $connection->save();
                        $connections_count++;
                    }
                }
            }
            //Проход №2
            foreach ($list as $k) {
                //По 13 столбцу определяем с чем мы имеем дело
                //Слово Порт, пропускаем это заголовки столбцов
                if ($k[12] == "Порт") {
                    continue;
                }
                //Пустое значение -> это папка соединения
                if ($k[12] == "") {
                    if ($k[19] != "") {
                        $unit = Units::findOne($units_ids[$k[17]]);
                        $unit->parent_id = $units_ids[$k[19]];
                        $unit->save();
                    }
                }
                //Непустое значение -> это какое-либо соединение
                else {
                    //Аналогично с подключенияеми
                    if ($k[19] != "") {

                        $connection = new Connections();
                        $connection->device_type_id = $deviceTypeId;
                        $connection->name = $k[11];
                        $connection->ipaddr = $k[10];
                        $connection->unit_id = $units_ids[$k[19]];
                        $connection->comment = "Импортировано из Radmin";
                        $connection->save();
                        $connections_count++;
                    }
                }
            }
        }
        return "При импорте из Radmin создано " . $unit_count . " папок, " . $connections_count . " подключений!";
    }


    public static function importMsRdpCM($rootUnitId, $deviceTypeId)
    {
        $unit_count = 0;
        $connections_count = 0;
        $connections = array();

        $xml = simplexml_load_file('uploads/import.xml', "SimpleXMLElement", LIBXML_NOWARNING);

        foreach ($xml->Event as $event) {
            //В журнале LocalSessionManager ищем события с EventID равным 24
            if ($event->System->EventID >= 20 and $event->System->EventID <= 29) {
                //Найденные соединения ложим в массив $connections, если их там нет и создаем новое подключение в БД.
                //В противном случае ничего не делаем.
                //События в журнале идут от позднего до самого раннего, нет смысла сохранять более ранний IP, он мог поменяться
                if (array_key_exists((string) $event->UserData->EventXML->User, $connections) == false) {
                    $connections[(string) $event->UserData->EventXML->User] =
                        (string) $event->UserData->EventXML->Address;
                    $connection = new Connections();
                    $connection->device_type_id = $deviceTypeId;
                    $connection->name = (string) $event->UserData->EventXML->User;
                    $connection->ipaddr = (string) $event->UserData->EventXML->Address;
                    $connection->unit_id = $rootUnitId;
                    $connection->comment = "Импортировано из MS LocalSessionManager";
                    $connection->save();
                    $connections_count++;
                }
            }
        }
        return "При импорте из MS  LocalSessionManager создано "  . $connections_count . " подключений!";
    }
}
