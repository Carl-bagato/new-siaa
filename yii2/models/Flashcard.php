<?php
namespace app\models;

use yii\db\ActiveRecord;

class Flashcard extends ActiveRecord
{
    // This method specifies the table name
    public static function tableName()
    {
        return 'flashcard';  // Ensure this matches the name of your table
    }

    // Define primary key method (optional, Yii usually detects this automatically)
    public static function primaryKey()
    {
        return ['flashcard_id'];  // Ensure this matches the primary key column in the table
    }

    // Define validation rules (optional)
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],  // Example validation rules
            [['title'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['user_id'], 'integer'],
            [['date_created'], 'safe'],
        ];
    }

    /**
     * Gets the user that owns the flashcard.
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    /**
     * Gets the terms associated with the flashcard.
     * @return \yii\db\ActiveQuery
     */
    public function getTermAnswers()
    {
        return $this->hasMany(TermAnswer::class, ['flashcard_id' => 'flashcard_id']);
    }
}
?>