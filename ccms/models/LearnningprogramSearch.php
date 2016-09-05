<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Learnningprogram;

/**
 * LearnningprogramSearch represents the model behind the search form about `app\models\Learnningprogram`.
 */
class LearnningprogramSearch extends Learnningprogram
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['learnningProgram_id', 'user_id'], 'integer'],
            [['learnningProgram_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Learnningprogram::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'learnningProgram_id' => $this->learnningProgram_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'learnningProgram_name', $this->learnningProgram_name]);

        return $dataProvider;
    }
}
