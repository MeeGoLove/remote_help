<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $importFile;
    public $rootUnitId;
    public $deviceTypeId;
    public $clearDir;

    public function rules()
    {
        return [
            [
                ['importFile'], 'file', 'skipOnEmpty' => false,
                //'extensions' => 'xml, csv, rpb'
            ],
            [['rootUnitId', 'deviceTypeId'], 'required'],
            ['clearDir', 'safe']
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
            'clearDir' => 'Очистить выбранную папку от подпапок и подключений'
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


    public static function importMsRdpLSM($rootUnitId, $deviceTypeId)
    {
        $notImported = "";
        $connections_count = 0;
        $connections = array();
        $xml = simplexml_load_file('uploads/import.xml', "SimpleXMLElement", LIBXML_NOWARNING);

        foreach ($xml->Event as $event) {
            //В журнале LocalSessionManager ищем события с EventID начинающимся с 2
            if ($event->System->EventID >= 20 and $event->System->EventID <= 29) {
                $name = (string) $event->UserData->EventXML->User;
                //Имя пользователя содержит имя домена, удаляем
                $pos = strpos($name, '\\');
                $name = substr($name, $pos + 1);
                $ipaddr = (string) $event->UserData->EventXML->Address;
                //Найденные соединения ложим в массив $connections, если их там нет и создаем новое подключение в БД.
                //В противном случае ничего не делаем.
                //События в журнале идут от позднего до самого раннего, нет смысла сохранять более ранний IP, он мог поменяться
                //Так же пропускаем события без IP
                if (array_key_exists($name, $connections) == false and  $ipaddr !== "") {
                    $connections[$name] =
                        $ipaddr;
                    //Отбрасывем учетки без IP
                    if ($ipaddr === '::%16777216') {
                        $notImported = $notImported . $name . ", ";
                        continue;
                    }
                    $connection = new Connections();
                    $connection->device_type_id = $deviceTypeId;
                    $connection->name = $name;
                    $connection->ipaddr = $ipaddr;
                    $connection->unit_id = $rootUnitId;
                    $connection->comment = "Импортировано из MS LocalSessionManager";
                    $connection->save();
                    $connections_count++;
                }
            }
        }
        $notImported = substr($notImported, 0, -1);
        return "При импорте из журнала TS LocalSessionManager создано "  . $connections_count . " подключений! Учетные записи "
            . $notImported . " не имеют IP-адреса, они не были импортированы!";
    }

    public static function importMsRdpGateway($rootUnitId, $deviceTypeId)
    {
        $connections_count = 0;
        $connections = array();
        $xml = simplexml_load_file('uploads/import.xml', "SimpleXMLElement", LIBXML_NOWARNING);

        foreach ($xml->Event as $event) {
            //В журнале Gateway ищем события с EventID 200
            if ($event->System->EventID == 200) {
                $name = (string) $event->UserData->EventInfo->Username;
                //Имя пользователя содержит имя домена, удаляем
                $pos = strpos($name, '\\');
                $name = substr($name, $pos + 1);
                $ipaddr = (string) $event->UserData->EventInfo->IpAddress;
                //Найденные соединения ложим в массив $connections, если их там нет и создаем новое подключение в БД.
                //В противном случае ничего не делаем.
                //События в журнале идут от позднего до самого раннего, нет смысла сохранять более ранний IP, он мог поменяться
                //Так же пропускаем события без IP
                if (array_key_exists($name, $connections) == false and  $ipaddr !== "") {
                    $connections[$name] =
                        $ipaddr;
                    $connection = new Connections();
                    $connection->device_type_id = $deviceTypeId;
                    $connection->name = $name;
                    $connection->ipaddr = $ipaddr;
                    $connection->unit_id = $rootUnitId;
                    $connection->comment = "Импортировано из MS Gateway";
                    $connection->save();
                    $connections_count++;
                }
            }
        }
        return "При импорте из журнала TS Gateway создано "  . $connections_count . " подключений!";
    }


    public static function importExcel($rootUnitId, $deviceTypeId)
    {
        $updatedCount = 0;
        $sFile = 'uploads/import.xlsx';
        $oSpreadsheet = IOFactory::load($sFile);
        $oCells = $oSpreadsheet->getActiveSheet()->getCellCollection();
        $username = "";
        $row = 2;
        while ($username != "Обозначения") {
            $userCell = $oCells->get('A' . $row);
            if ($userCell) {
                $username = $userCell->getValue();
            }
            $name1Cell = $oCells->get('B' . $row);
            if ($name1Cell) {
                $name1 = $name1Cell->getValue();
            } else {
                $name1 = "";
            }
            $name2Cell = $oCells->get('C' . $row);
            if ($name2Cell) {
                $name2 = $name2Cell->getValue();
            } else {
                $name2 = "";
            }
            $connection = Connections::findOne(['name' => $username, 'unit_id' => $rootUnitId]);
            if ($connection) {
                $connection->name = $name1 . " / " . $name2 . " " . $connection->name;
                $connection->save();
                $updatedCount++;
            }
            $row++;
        }
        return "При импорте из выданных терминалок обновлено "  . $updatedCount . " подключений!";
    }
}
