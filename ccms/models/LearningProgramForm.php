<?php

namespace app\models;
use yii\base\Model;
use app\models\Learnningprogram;
use Yii;

class LearningProgramForm extends Model{
    public $learningProgramName;

    public function rules()
    {
        return [
            [['learningProgramName'], 'required'],
        ];
    }

    public function saveLearningProgram(){

        if ($this->validate()) {
            $learningprog = new Learnningprogram();
            $learningprog->learnningProgram_name = $this->learningProgramName;
            $learningprog->user_id =Yii::$app->user->id;
            if($learningprog->save()){
                return true;
            }
        }
        return false;
    }
}