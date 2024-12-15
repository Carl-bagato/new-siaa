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
            [['flashcard_id', 'term', 'answer'], 'required'],  // Ensure all fields are present
            [['flashcard_id'], 'integer'],  // Validate flashcard_id as integer
            [['term', 'answer'], 'string'],  // Both term and answer can be string (no length restriction)
            [['answer'], 'string', 'max' => 1000],  // Allow longer answers if necessary
        ];
    }
}

?>