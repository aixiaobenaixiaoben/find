<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\profile\base;

use Yii;

/**
 * This is the base-model class for table "profile".
 *
 * @property integer $id
 * @property integer $event_id
 * @property string $name
 * @property integer $age
 * @property string $gender
 * @property integer $height
 * @property string $dress
 * @property string $appearance
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \common\models\event\Event $event
 */
abstract class Profile extends \yii\db\ActiveRecord
{



    /**
    * ENUM field values
    */
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    var $enum_labels = false;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'name', 'age', 'height', 'dress'], 'required'],
            [['event_id', 'age', 'height'], 'integer'],
            [['gender'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 40],
            [['dress', 'appearance'], 'string', 'max' => 255],
            ['gender', 'in', 'range' => [
                    self::GENDER_MALE,
                    self::GENDER_FEMALE,
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
            'name' => 'Name',
            'age' => 'Age',
            'gender' => 'Gender',
            'height' => 'Height',
            'dress' => 'Dress',
            'appearance' => 'Appearance',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(\common\models\event\Event::className(), ['id' => 'event_id']);
    }




    /**
     * get column gender enum value label
     * @param string $value
     * @return string
     */
    public static function getGenderValueLabel($value){
        $labels = self::optsGender();
        if(isset($labels[$value])){
            return $labels[$value];
        }
        return $value;
    }

    /**
     * column gender ENUM value labels
     * @return array
     */
    public static function optsGender()
    {
        return [
            self::GENDER_MALE => 'Male',
            self::GENDER_FEMALE => 'Female',
        ];
    }

}
