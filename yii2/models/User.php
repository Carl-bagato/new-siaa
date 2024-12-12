<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user'; // Matches the table name in the database
    }

    public function rules()
    {
        return [
            [['user_name', 'password'], 'required'],
            [['user_name'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 1],
            [['user_name'], 'unique', 'targetClass' => self::class, 'message' => 'This username has already been taken.'],
       ];
    }

    // Find user by username
    public static function findByUsername($user_name)
    {
        return static::findOne(['user_name' => $user_name]); // Correct the column name to 'user_name'
    }

    // Validate password
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password); // Ensure passwords are hashed
    }

    // IdentityInterface methods
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    public function getId()
    {
        return $this->user_id; // Ensure this matches the primary key column
    }

    public function getAuthKey()
    {
        return $this->auth_key ?? null;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    

    
}

?>