<?php
// models/SignupForm.php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class SignupForm extends Model
{
    public $user_name;
    public $password;
    public $confirmPassword;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_name', 'password', 'confirmPassword'], 'required'],
            [['user_name'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 6],  // Password minimum length
            [['confirmPassword'], 'compare', 'compareAttribute' => 'password'], // Password match
            [['user_name'], 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'], // Check for unique username
        ];
    }

    /**
     * Signs up a user.
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->user_name = $this->user_name;
            $user->password = Yii::$app->security->generatePasswordHash($this->password);  // Hash the password before saving
            
            // Attempt to save the user to the database
            if ($user->save()) {
                
                return true; // Indicate that the signup was successful
            }
        }
        return false; // If validation or saving fails, return false
    }
}
?>