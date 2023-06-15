<?php
namespace app\models;

/**
 * @property Connections[] $connections
 */

class ConnectionStats extends \yii\db\ActiveRecord
{
    public $counters;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'connection_stats';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['connection_id', 'operator_ip'], 'required'],
            [['connection_id', 'connection_date'], 'integer'],
            [['operator_ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'connection_id' => 'Connection ID',
            'operator_ip' => 'Operator Ip',
            'connection_date' => 'Connection Date',
        ];
    }
    /**
     * Gets query for [[Connections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConnections()
    {
        return $this->hasOne(Connections::className(), ['id' => 'connection_id']);
    }
}
?>
