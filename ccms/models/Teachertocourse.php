<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teachertocourse".
 *
 * @property integer $id
 * @property integer $learnningProgram_id
 * @property integer $teacher_id
 * @property integer $course_id
 * @property integer $user_id
 *
 * @property Teacher $teacher
 * @property User $user
 * @property Course $course
 */
class Teachertocourse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teachertocourse';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['learnningProgram_id', 'teacher_id', 'course_id', 'user_id'], 'required'],
            [['learnningProgram_id', 'teacher_id', 'course_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'learnningProgram_id' => 'Learnning Program ID',
            'teacher_id' => 'Teacher ID',
            'course_id' => 'Course ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::className(), ['teacher_id' => 'teacher_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['course_id' => 'course_id']);
    }
}
