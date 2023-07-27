<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "connections".
 *
 * @property int $id
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
            [['name', 'ipaddr', 'comment'], 'string', 'max' => 255],
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
        $data = Connections::find()->where(['unit_id' => $unit_id])->orderBy(['name' => 'SORT_ASC']);
        return $data;
    }

    public static function connectionsBySearch($keyword, $onlyName)
    {
        if ($onlyName) {
            $data = Connections::find()->where(['like', 'name', '%' . $keyword . '%', false])->orderBy(['name' => 'SORT_ASC']);
            return $data;
        } else {
            $data = Connections::find()->where(['like', 'name', '%' . $keyword . '%', false])->orWhere(['like', 'ipaddr', '%' . $keyword . '%', false])->orderBy(['name' => 'SORT_ASC']);
            return $data;
        }
    }
}
