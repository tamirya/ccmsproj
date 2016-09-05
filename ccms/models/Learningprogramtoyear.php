<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "learningprogramtoyear".
 *
 * @property integer $learningprogramtoyear_id
 * @property integer $learnningProgram_id
 * @property integer $yearAndSemester_id
 * @property integer $user_id
 *
 * @property Learnningprogram $learnningProgram
 * @property Yearandsemester $yearAndSemester
 */
class Learningprogramtoyear extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'learningprogramtoyear';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['learnningProgram_id', 'yearAndSemester_id', 'user_id'], 'required'],
            [['learnningProgram_id', 'yearAndSemester_id', 'user_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'learningprogramtoyear_id' => 'Learningprogramtoyear ID',
            'learnningProgram_id' => 'Learnning Program ID',
            'yearAndSemester_id' => 'Year And Semester ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLearnningProgram()
    {
        return $this->hasOne(Learnningprogram::className(), ['learnningProgram_id' => 'learnningProgram_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getYearAndSemester()
    {
        return $this->hasOne(Yearandsemester::className(), ['yearAndSemester_id' => 'yearAndSemester_id']);
    }
}
