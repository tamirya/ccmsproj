<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ccmsfinalauto".
 *
 * @property integer $learnningProgram_id
 * @property integer $course_id
 * @property integer $timedata
 *
 * @property Course $course
 * @property Learnningprogram $learnningProgram
 */
class Ccmsfinalauto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ccmsfinalauto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['learnningProgram_id', 'course_id', 'timedata'], 'required'],
            [['learnningProgram_id', 'course_id', 'timedata'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'learnningProgram_id' => 'Learnning Program ID',
            'course_id' => 'Course ID',
            'timedata' => 'Timedata',
        ];
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
    public function getLearnningProgram()
    {
        return $this->hasOne(Learnningprogram::className(), ['learnningProgram_id' => 'learnningProgram_id']);
    }
}
