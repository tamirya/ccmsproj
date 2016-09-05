<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coursetoyear".
 *
 * @property integer $id
 * @property integer $learnningProgram_id
 * @property integer $yearAndSemester_id
 * @property integer $course_id
 * @property integer $user_id
 *
 * @property Yearandsemester $yearAndSemester
 * @property Course $course
 * @property User $user
 */
class Coursetoyear extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coursetoyear';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['learnningProgram_id', 'yearAndSemester_id', 'course_id', 'user_id'], 'required'],
            [['learnningProgram_id', 'yearAndSemester_id', 'course_id', 'user_id'], 'integer']
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
            'yearAndSemester_id' => 'Year And Semester ID',
            'course_id' => 'Course ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYearAndSemester()
    {
        return $this->hasOne(Yearandsemester::className(), ['yearAndSemester_id' => 'yearAndSemester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['course_id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
