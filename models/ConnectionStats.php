<?php
namespace app\models;

use Yii;
class ConnectionStats extends \yii\db\ActiveRecord
{
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
}

?>
