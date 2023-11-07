<?php

namespace app\models;

use Yii;

$unit_res = [];
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
class Units extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'units';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'location' => 'Расположение',
            'parent_id' => 'ID родителя',
        ];
    }

    /**
     * Gets query for [[Connections]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConnections()
    {
        return $this->hasMany(Connections::className(), ['unit_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Units::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Units]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnits()
    {
        return $this->hasMany(Units::className(), ['parent_id' => 'id']);
    }

    public static function unitsDropdownList()
    {
        global $unit_res;
        $unit_res = [];
        Units::childsTree();
        return array_filter($unit_res);
    }

    public static function childsTree($parent_id = null, $nesting_level = 0)
    {
        global $unit_res;
        $units = Units::find()->where(['parent_id' => $parent_id])->orderBy('name ASC')->asArray()->all();
        $nesting_level++;
        foreach ($units as $unit) {
            $name = $unit['name'];
            //$name = '|__ ' . $name;
            for ($i = 1; $i < $nesting_level; $i++) {
                $name = '    ' . $name;
            }
            array_push($unit_res, [
                'id' => $unit['id'],
                'name' => $name
            ]);
            array_push($unit_res, Units::childsTree($unit['id'], $nesting_level));
        }
    }

    public static function childUnitsByUnitId($unit_id)
    {
        $data =  Units::find()->where(['parent_id' => $unit_id])->orderBy('name');
        return $data;
    }


    public static function unitsBySearch($keyword)
    {       
        $layout = Connections::switcher($keyword, 1);
        $layout1 = Connections::switcher($keyword, 2);
        $data = Units::find()->where(['like', 'name', '%' . $keyword . '%', false])->orWhere(['like', 'name', '%' . $layout . '%', false])->orWhere(['like', 'name', '%' . $layout1 . '%', false])->orderBy(['name' => 'SORT_ASC']);
        return $data;
    }

    public static function unitsBreadCrumbs($unit_id)
    {
        $unit = Units::findOne($unit_id);

        if ($unit !== null) {
            if ($unit->parent_id !== null) {
                return Units::unitsBreadCrumbs($unit->parent_id) . "<li><a href='/tree/index?unit_id=$unit->id'>$unit->name </a></li>";
            } else {
                return "<li><a href='/tree/index?unit_id=$unit->id'>$unit->name </a></li>";
            }
        } else {
            return "<li> Корневой элемент не найден!</li>";
        }
    }

    /**
     * Override fields function for working REST API
     */
    public function fields()
    {
        return [
            'id',
            'label' => 'name',
            'parentId' => 'parent_id',
            'items' => function () {
                return null;
            }
        ];
    }
}
