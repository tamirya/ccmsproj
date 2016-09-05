<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teachersconstrains".
 *
 * @property integer $teachersconstrains_id
 * @property integer $learnningProgram_id
 * @property integer $teacher_id
 * @property string $title
 * @property string $startdate
 * @property string $enddate
 *
 * @property Teacher $teacher
 */
class Teachersconstrains extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teachersconstrains';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['learnningProgram_id'], 'required'],
            [['learnningProgram_id', 'teacher_id'], 'integer'],
            [['title', 'startdate', 'enddate'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'teachersconstrains_id' => 'Teachersconstrains ID',
            'learnningProgram_id' => 'Learnning Program ID',
            'teacher_id' => 'Teacher ID',
            'title' => 'Title',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['teacher_id' => 'teacher_id']);
    }
}
