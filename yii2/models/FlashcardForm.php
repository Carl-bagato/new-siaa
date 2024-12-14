<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Flashcard;
use app\models\TermAnswer;

class FlashcardForm extends Model
{
    public $set_title;
    public $set_description;
    public $terms = [];
    public $definitions = [];

    public function rules()
    {
        return [
            [['set_title', 'set_description'], 'required', 'message' => 'This field is required.'],
            [['set_title'], 'string', 'max' => 255],
            [['set_description'], 'string'],
            [['terms', 'definitions'], 'each', 'rule' => ['required', 'message' => 'Please provide terms and definitions.']],
            [['terms', 'definitions'], 'each', 'rule' => ['string']],
            [['terms', 'definitions'], 'validateTermsAndDefinitions'], // Custom validator
        ];
    }
    public function validateTermsAndDefinitions($attribute)
    {
        if (count($this->terms) !== count($this->definitions)) {
            $this->addError($attribute, 'The number of terms and definitions must match.');
        }
    }

     /**
     * Save the flashcard set along with its terms and answers.
     * @param int $user_id The ID of the user creating the flashcard set.
     * @return Flashcard The created Flashcard model.
     * @throws \Exception
     */
    public function saveFlashcardSet($user_id)
{
    $transaction = \Yii::$app->db->beginTransaction(); // Start a DB transaction
    try {
        // Save Flashcard Set
        $flashcard = new Flashcard();
        $flashcard->user_id = $user_id;
        $flashcard->title = $this->set_title;
        $flashcard->content = $this->set_description ?: 'No content provided'; // Default content if empty
        if (!$flashcard->save()) {
            Yii::error('Flashcard save failed: ' . json_encode($flashcard->errors), __METHOD__);
            throw new \Exception('Failed to save flashcard set.');
        }

        // Save Terms and Answers
        foreach ($this->terms as $index => $term) {
            $answer = $this->definitions[$index] ?? null; // Safely get definition, default to null if not set
            $termAnswer = new TermAnswer();
            $termAnswer->flashcard_id = $flashcard->id;
            $termAnswer->term = $term;
            $termAnswer->answer = $answer;

            if (!$termAnswer->save()) {
                Yii::error('TermAnswer save failed: ' . json_encode($termAnswer->errors), __METHOD__);
                throw new \Exception('Failed to save term and answer.');
            }
        }

        $transaction->commit();
        return $flashcard;
    } catch (\Exception $e) {
        $transaction->rollBack();
        Yii::error('Error saving flashcard set: ' . $e->getMessage(), __METHOD__);
        throw $e;
    }
}



}
    

?>