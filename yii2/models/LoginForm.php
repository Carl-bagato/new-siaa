<?php
// models/LoginForm.php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User; // Make sure to import the User model, assuming this is where the authentication logic is defined.

class LoginForm extends Model
{
    public $user_name;
    public $password;

    private $_user = false;

    // Validation rules
    public function rules()
    {
        return [
            [['user_name', 'password'], 'required'], // Ensure both are required
            ['user_name', 'string', 'max' => 255],    // Optional: You can add length validation for user_name
            ['password', 'string'],                   // Optional: You can add length validation for password
        ];
    }

    public function attributes()
    {
        return ['user_name', 'password'];
    }

    // Validate the password
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
    

    // Find the user by username
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->user_name); // Assuming `User::findByUsername` method exists in the User model
        }

        return $this->_user;
    }

    // Login the user if validation is successful
    public function login()
    {
        if ($this->validate()) {
            // Find the user by username
            $user = User::findByUsername($this->user_name); 
            if ($user && Yii::$app->security->validatePassword($this->password, $user->password)) {
                return Yii::$app->user->login($user);  // Log in the user
            }
            // If validation fails, add error to password field
            $this->addError('password', 'Incorrect username or password.');
        }
        return false;  // Return false if the login failed
    }
}
?>
