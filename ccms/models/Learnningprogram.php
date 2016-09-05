<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "learnningprogram".
 *
 * @property integer $learnningProgram_id
 * @property integer $user_id
 * @property string $learnningProgram_name
 *
 * @property Ccmsfinalauto[] $ccmsfinalautos
 * @property Coursetoyear[] $coursetoyears
 * @property Learningprogramtoyear[] $learningprogramtoyears
 * @property User $user
 * @property Teachersconstrains[] $teachersconstrains
 * @property Teachertocourse[] $teachertocourses
 */
class Learnningprogram extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'learnningprogram';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'learnningProgram_name'], 'required'],
            [['user_id'], 'integer'],
            [['learnningProgram_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'learnningProgram_id' => 'Learnning Program ID',
            'user_id' => 'User ID',
            'learnningProgram_name' => 'Learnning Program Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCcmsfinalautos()
    {
        return $this->hasMany(Ccmsfinalauto::className(), ['learnningProgram_id' => 'learnningProgram_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoursetoyears()
    {
        return $this->hasMany(Coursetoyear::className(), ['learnningProgram_id' => 'learnningProgram_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLearningprogramtoyears()
    {
        return $this->hasMany(Learningprogramtoyear::className(), ['learnningProgram_id' => 'learnningProgram_id']);
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
    public function getTeachersconstrains()
    {
        return $this->hasMany(Teachersconstrains::className(), ['learnningProgram_id' => 'learnningProgram_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeachertocourses()
    {
        return $this->hasMany(Teachertocourse::className(), ['learnningProgram_id' => 'learnningProgram_id']);
    }
}
