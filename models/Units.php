<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "units".
 *
 * @property int $id
 * @property string $name
 * @property string|null $location
 * @property int|null $parent_id
 *
 * @property Connections[] $connections
 * @property Units $parent
 * @property Units[] $units
 */
$unit_res = [];

class Units extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'units';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['parent_id'], 'integer'],
            [['name', 'location'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Units::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'location' => 'Location',
            'parent_id' => 'Parent ID',
        ];
    }

    /**
     * Gets query for [[Connections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConnections() {
        return $this->hasMany(Connections::className(), ['unit_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasOne(Units::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Units]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnits() {
        return $this->hasMany(Units::className(), ['parent_id' => 'id']);
    }

    public static function unitsDropdownList() {
        global $unit_res;
        $unit_res = [];
        Units::childsTree();
        return array_filter($unit_res);
    }

    public static function childsTree($parent_id = null, $nesting_level = 0) {
        global $unit_res;
        $units = Units::find()->where(['parent_id' => $parent_id])->orderBy('name ASC')->asArray()->all();
        $nesting_level++;
        foreach ($units as $unit) {
            $name = $unit['name'];
            for ($i = 1; $i < $nesting_level; $i++) {
                $name = ' - ' . $name;
            }
            array_push($unit_res, ['id' => $unit['id'],
                'name' => $name]);
            array_push($unit_res, Units::childsTree($unit['id'], $nesting_level));
        }
    }

}
