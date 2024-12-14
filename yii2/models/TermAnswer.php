<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "term_answer".
 *
 * @property int $term_answer_id
 * @property int $flashcard_id
 * @property string $term
 * @property string $answer
 */
class TermAnswer extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'term_answer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['flashcard_id', 'term', 'answer'], 'required'],
            [['term_answer_id', 'flashcard_id'], 'integer'],
            [['answer'], 'string', 'max' => 255],
            [['term'], 'string', 'max' => 255],
            [['answer'], 'string'],
        ];
    }

    /**
     * Gets the flashcard that owns the term-answer pair.
     * @return \yii\db\ActiveQuery
     */
    public function getFlashcard()
    {
        return $this->hasOne(Flashcard::class, ['flashcard_id' => 'flashcard_id']);
    }
}
?>