<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;

/**
 * This is the model class for table "connection_types".
 *
 * @property int $id
 * @property string $name
 * @property string $protocol_link
 * @property string|null $icon
 * @property string|null $protocol_link_readonly
 * @property string|null $protocol_link_telnet
 * @property string|null $protocol_link_file_transfer
 * @property string|null $protocol_link_power
 * @property string|null $port
 *
 * @property DeviceTypes[] $deviceTypes
 */
class ConnectionTypes extends ActiveRecord
{

    /**
     * Вспомогательный атрибут для загрузки изображения
     */
    public $upload;

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
            [['name', 'protocol_link', 'protocol_link_readonly', 'protocol_link_telnet', 'protocol_link_file_transfer', 'protocol_link_power', 'icon', 'port'], 'string', 'max' => 255],
            // атрибут icon проверяем с помощью валидатора image
            ['icon', 'image', 'extensions' => 'png, jpg, gif'],
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
            'protocol_link' => 'Ссылка для управление',
            'protocol_link_readonly' => 'Ссылка для просмотра',
            'protocol_link_telnet' => 'Ссылка для TELNET',
            'protocol_link_file_transfer' => 'Ссылка для передачи файлов',
            'protocol_link_power' => 'Ссылка для управления питанием',
            'icon' => 'Иконка',
            'port' => 'Порт(ы) подключения, если несколько, то через запятую',
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

    /**
     * Загружает файл изображения категории
     */
    public function uploadIcon()
    {
        if ($this->upload) { // только если был выбран файл для загрузки
            $name = md5(uniqid(rand(), true)) . '.' . $this->upload->extension;
            // сохраняем исходное изображение в директории source
            $source = Yii::getAlias('@webroot/icons-remote/source/' . $name);
            if ($this->upload->saveAs($source)) {
                // выполняем resize, чтобы получить маленькое изображение
                $thumb = Yii::getAlias('@webroot/icons-remote/thumb/' . $name);
                Image::thumbnail($source, 50, 50)->save($thumb, ['quality' => 100]);
                return $name;
            }
        }
        return false;
    }

    /**
     * Удаляет старое изображение при загрузке нового
     */
    public static function removeIcon($name)
    {
        if (!empty($name)) {
            $source = Yii::getAlias('@webroot/icons-remote/source/' . $name);
            if (is_file($source)) {
                unlink($source);
            }
            $thumb = Yii::getAlias('@webroot/icons-remote/thumb/' . $name);
            if (is_file($thumb)) {
                unlink($thumb);
            }
        }
    }

    /**
     * Удаляет изображение при удалении категории
     */
    public function afterDelete()
    {
        parent::afterDelete();
        self::removeIcon($this->image);
    }
}
