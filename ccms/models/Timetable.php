<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "timetable".
 *
 * @property integer $timetableid
 * @property integer $yearAndSemester_id
 * @property string $title
 * @property string $startdate
 * @property string $enddate
 * @property integer $learnningProgram_id
 *
 * @property Learnningprogram $learnningProgram
 * @property Yearandsemester $yearAndSemester
 */
class Timetable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'timetable';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['yearAndSemester_id', 'learnningProgram_id'], 'required'],
            [['yearAndSemester_id', 'learnningProgram_id'], 'integer'],
            [['title', 'startdate', 'enddate'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'timetableid' => 'Timetableid',
            'yearAndSemester_id' => 'Year And Semester ID',
            'title' => 'Title',
            'startdate' => 'Startdate',
            'enddate' => 'Enddate',
            'learnningProgram_id' => 'Learnning Program ID',
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
