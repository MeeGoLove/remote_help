<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "device_types".
 *
 * @property int $id
 * @property string $name
 * @property int $default_connection_type_id
 * @property int|null $optional_connection_type_id
 *
 * @property Connections[] $connections
 * @property ConnectionTypes $defaultConnectionType
 * @property ConnectionTypes $optionalConnectionType
 */
class DeviceTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'device_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'default_connection_type_id'], 'required'],
            [['default_connection_type_id', 'optional_connection_type_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['default_connection_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConnectionTypes::className(), 'targetAttribute' => ['default_connection_type_id' => 'id']],
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
            'default_connection_type_id' => 'Удаленный протокол по умолчанию',
            'optional_connection_type_id' => 'Удаленный протокол дополнительный',
        ];
    }

    /**
     * Gets query for [[Connections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConnections()
    {
        return $this->hasMany(Connections::className(), ['device_type_id' => 'id']);
    }

    /**
     * Gets query for [[DefaultConnectionType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDefaultConnectionType()
    {
        return $this->hasOne(ConnectionTypes::className(), ['id' => 'default_connection_type_id']);
    }
    
    /**
     * Gets query for [[DefaultConnectionType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptionalConnectionType()
    {
        return $this->hasOne(ConnectionTypes::className(), ['id' => 'optional_connection_type_id']);
    }
}
