<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\db\Query;

$rows = (new Query())
    ->from('your_table')
    ->all();

foreach ($rows as $row) {
    // Process each row
}

$this->title = 'Login - Quick Recall';
$this->params['breadcrumbs'][] = $this->title;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        color: #262626;
    }
    .login-container { background-color: #f9f9f9; padding: 20px; }
    .card { border-radius: 12px; }
    .card h3 { font-weight: 600; color: #333; }
    .btn-primary-login {
        color: #fefefe; background-color: #086942; border: none; 
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-primary-login:hover {
        color: #fefefe; background-color: #065533; transform: scale(1.02);
    }
    a { color: #086942; text-decoration: none; }
    a:hover { color: #065533; text-decoration: none; }
</style>

<body>

    <div class="login-container d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-lg border-0 p-4" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-4">
                <h3 class="mt-3">Login</h3>
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'method' => 'POST',
                'action' => Url::to(['site/login']),  // Yii2 action URL for login
                'options' => ['onsubmit' => 'return validateForm()'],
            ]); ?>

            <div class="mb-3">
                <?= $form->field($model, 'username')->textInput([
                    'class' => 'form-control',
                    'placeholder' => 'Enter your username',
                    'required' => true,
                ])->label('Username', ['class' => 'form-label']) ?>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'password')->passwordInput([
                    'class' => 'form-control',
                    'placeholder' => 'Enter your password',
                    'required' => true,
                ])->label('Password', ['class' => 'form-label']) ?>
            </div>

            <button type="submit" class="btn btn-primary-login w-100">Login</button>

            <?php ActiveForm::end(); ?>

            <div class="text-center mt-3">
                <p class="small">Don't have an account? <?= Html::a('Sign Up', ['site/signup']) ?></p>
            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
function validateForm() {
    const username = document.getElementById("loginform-username").value;
    const password = document.getElementById("loginform-password").value;

    if (username === "" || password === "") {
        alert("Please fill in both fields.");
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}
</script>

</body>
</html>
