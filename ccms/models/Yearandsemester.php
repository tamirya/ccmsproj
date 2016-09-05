<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "yearandsemester".
 *
 * @property integer $yearAndSemester_id
 * @property string $yearAndSemester_name
 *
 * @property Coursetoyear[] $coursetoyears
 * @property Learningprogramtoyear[] $learningprogramtoyears
 * @property Teachersconstrains[] $teachersconstrains
 */
class Yearandsemester extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yearandsemester';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['yearAndSemester_name'], 'required'],
            [['yearAndSemester_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'yearAndSemester_id' => 'Year And Semester ID',
            'yearAndSemester_name' => 'Year And Semester Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoursetoyears()
    {
        return $this->hasMany(Coursetoyear::className(), ['yearAndSemester_id' => 'yearAndSemester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLearningprogramtoyears()
    {
        return $this->hasMany(Learningprogramtoyear::className(), ['yearAndSemester_id' => 'yearAndSemester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeachersconstrains()
    {
        return $this->hasMany(Teachersconstrains::className(), ['yearAndSemester_id' => 'yearAndSemester_id']);
    }
}
