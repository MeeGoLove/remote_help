<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "connections".
 *
 * @property int $id
 * @property string $hostname
 * @property string $name
 * @property string $ipaddr
 * @property string|null $comment
 * @property int $device_type_id
 * @property int|null $unit_id
 * @property int|null $count_connect
 *
 * @property ConnectionStats[] $connectionStats
 * @property DeviceTypes $deviceType
 * @property Units $unit
 */
class Connections extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'connections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'ipaddr', 'device_type_id'], 'required'],
            [['device_type_id', 'unit_id', 'count_connect'], 'integer'],
            [['name', 'hostname' , 'ipaddr', 'comment'], 'string', 'max' => 255],
            [['device_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeviceTypes::className(), 'targetAttribute' => ['device_type_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Units::className(), 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hostname' => 'DNS имя хоста',
            'name' => 'Имя',
            'ipaddr' => 'IP-адрес',
            'comment' => 'Комментарий',
            'device_type_id' => 'Тип устройства',
            'unit_id' => 'Папка подключения',
            'count_connect' => 'Было подключений',
        ];
    }

    /**
     * Gets query for [[ConnectionStats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConnectionStats()
    {
        return $this->hasMany(ConnectionStats::className(), ['connection_id' => 'id']);
    }

    /**
     * Gets query for [[DeviceType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceType()
    {
        return $this->hasOne(DeviceTypes::className(), ['id' => 'device_type_id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Units::className(), ['id' => 'unit_id']);
    }

    public static function connectionsByUnitId($unit_id)
    {
        $data = Connections::find()->where(['unit_id' => $unit_id])->orderBy(['hostname' => 'SORT_ASC','name' => 'SORT_ASC']);
        return $data;
    }

    public static function connectionsBySearch($keyword, $strictIp, $onlyNames)
    {

        $layout = self::switcher($keyword,1);
        $layout1 = self::switcher($keyword,2);
        if ($onlyNames) {
            $data = Connections::find()->where(['like', 'name', '%' . $keyword . '%', false])->
            orWhere(['like', 'name', '%' . $layout . '%', false])->
            orWhere(['like', 'name', '%' . $layout1 . '%', false])->
            orWhere(['like', 'hostname', '%' . $keyword . '%', false])->
            orWhere(['like', 'hostname', '%' . $layout . '%', false])->
            orWhere(['like', 'hostname', '%' . $layout1 . '%', false])->
            orderBy(['hostname' => 'SORT_ASC','name' => 'SORT_ASC']);
            return $data;            
    }
    else {      
        if ($strictIp) {
            $data = Connections::find()->where(['like', 'ipaddr',  $keyword , false])->orderBy(['hostname' => 'SORT_ASC','name' => 'SORT_ASC']);
            return $data;
        } else {
            $data = Connections::find()->where(['like', 'name', '%' . $keyword . '%', false])->
            orWhere(['like', 'name', '%' . $layout . '%', false])->
            orWhere(['like', 'name', '%' . $layout1 . '%', false])->
            orWhere(['like', 'hostname', '%' . $keyword . '%', false])->
            orWhere(['like', 'hostname', '%' . $layout . '%', false])->
            orWhere(['like', 'hostname', '%' . $layout1 . '%', false])->
            orWhere(['like', 'ipaddr', '%' . $keyword . '%', false])->orderBy(['hostname' => 'SORT_ASC','name' => 'SORT_ASC']);
            return $data;
        }      
    }
    }


    public static function connectionsByiP($ipaddr, $id)
    {

        $data = Connections::find()->where(['ipaddr' => $ipaddr])->andWhere(['NOT IN', 'id', $id]);
        return $data;
    }


    public static function switcher($text, $arrow = 0)
        {
            $str[0] = array('й' => 'q', 'ц' => 'w', 'у' => 'e', 'к' => 'r', 'е' => 't', 'н' => 'y', 'г' => 'u', 'ш' => 'i', 'щ' => 'o', 'з' => 'p', 'х' => '[', 'ъ' => ']', 'ф' => 'a', 'ы' => 's', 'в' => 'd', 'а' => 'f', 'п' => 'g', 'р' => 'h', 'о' => 'j', 'л' => 'k', 'д' => 'l', 'ж' => ';', 'э' => '\'', 'я' => 'z', 'ч' => 'x', 'с' => 'c', 'м' => 'v', 'и' => 'b', 'т' => 'n', 'ь' => 'm', 'б' => ',', 'ю' => '.', 'Й' => 'Q', 'Ц' => 'W', 'У' => 'E', 'К' => 'R', 'Е' => 'T', 'Н' => 'Y', 'Г' => 'U', 'Ш' => 'I', 'Щ' => 'O', 'З' => 'P', 'Х' => '[', 'Ъ' => ']', 'Ф' => 'A', 'Ы' => 'S', 'В' => 'D', 'А' => 'F', 'П' => 'G', 'Р' => 'H', 'О' => 'J', 'Л' => 'K', 'Д' => 'L', 'Ж' => ';', 'Э' => '\'', '?' => 'Z', 'ч' => 'X', 'С' => 'C', 'М' => 'V', 'И' => 'B', 'Т' => 'N', 'Ь' => 'M', 'Б' => ',', 'Ю' => '.',);
            $str[1] = array('q' => 'й', 'w' => 'ц', 'e' => 'у', 'r' => 'к', 't' => 'е', 'y' => 'н', 'u' => 'г', 'i' => 'ш', 'o' => 'щ', 'p' => 'з', '[' => 'х', ']' => 'ъ', 'a' => 'ф', 's' => 'ы', 'd' => 'в', 'f' => 'а', 'g' => 'п', 'h' => 'р', 'j' => 'о', 'k' => 'л', 'l' => 'д', ';' => 'ж', '\'' => 'э', 'z' => 'я', 'x' => 'ч', 'c' => 'с', 'v' => 'м', 'b' => 'и', 'n' => 'т', 'm' => 'ь', ',' => 'б', '.' => 'ю', 'Q' => 'Й', 'W' => 'Ц', 'E' => 'У', 'R' => 'К', 'T' => 'Е', 'Y' => 'Н', 'U' => 'Г', 'I' => 'Ш', 'O' => 'Щ', 'P' => 'З', '[' => 'Х', ']' => 'Ъ', 'A' => 'Ф', 'S' => 'Ы', 'D' => 'В', 'F' => 'А', 'G' => 'П', 'H' => 'Р', 'J' => 'О', 'K' => 'Л', 'L' => 'Д', ';' => 'Ж', '\'' => 'Э', 'Z' => '?', 'X' => 'ч', 'C' => 'С', 'V' => 'М', 'B' => 'И', 'N' => 'Т', 'M' => 'Ь', ',' => 'Б', '.' => 'Ю',);
            return strtr($text, isset($str[$arrow]) ? $str[$arrow] : array_merge($str[0], $str[1]));
        }
}
