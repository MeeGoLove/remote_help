<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "connection_types".
 *
 * @property int $id
 * @property string $name
 * @property string $protocol_link
 * @property string|null $icon
 *
 * @property DeviceTypes[] $deviceTypes
 */
class ConnectionTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'connection_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'protocol_link'], 'required'],
            [['name', 'protocol_link', 'icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'protocol_link' => 'Protocol Link',
            'icon' => 'Icon',
        ];
    }

    /**
     * Gets query for [[DeviceTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceTypes()
    {
        return $this->hasMany(DeviceTypes::className(), ['default_connection_type_id' => 'id']);
    }
}
