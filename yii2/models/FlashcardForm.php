<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Flashcard;
use app\models\TermAnswer;

class FlashcardForm extends Model
{
    public $id;
    public $set_title;
    public $set_description;
    public $terms = [];
    public $definitions = [];

    public function rules()
    {
        return [
            [['set_title', 'set_description'], 'required'],
            [['set_title', 'set_description'], 'string'],
            [['terms', 'definitions'], 'safe'],  // Make sure these fields are validated as safe
        ];
    }

     /**
     * Save the flashcard set along with its terms and answers.
     * @param int $user_id The ID of the user creating the flashcard set.
     * @return Flashcard The created Flashcard model.
     * @throws \Exception
     */
   
}
?>