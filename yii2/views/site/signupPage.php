<!-- views/site/signup.php -->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile("@web/css/site.css");

?>
<div class="home">
        <a href="<?= Url::to(['site/index']) ?>" class="btn btn-primary">Back to Home</a>
</div>
<div class="site-signup">
    <div class="card shadow-lg border-0 p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="mt-3"><?= Html::encode($this->title) ?></h3>
        </div>

        <?php $form = ActiveForm::begin(); ?>

            <div class="mb-3">
                <?= $form->field($model, 'user_name')->textInput(['placeholder' => 'Enter your username'])->label('Username') ?>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Create a password'])->label('Password') ?>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'confirmPassword')->passwordInput(['placeholder' => 'Confirm your password'])->label('Confirm Password') ?>
            </div>

            <div class="mb-3">
                <?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary-signup w-100']) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <div class="text-center mt-3">
            <p class="small">Already have an account? <?= Html::a('Login', ['site/login']) ?></p>
        </div>
    </div>
</div>

<?php
// Optionally, include your custom CSS styles for the page
$this->registerCss("
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        color: #262626;
    }
    .btn-primary-signup {
        background-color: #73d4eb;
        border: none;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-primary-signup:hover {
        background-color: #58b9d0;
        transform: scale(1.02);
    }
    a {
        color: #73d4eb;
        text-decoration: none;
    }
    a:hover {
        color: #58b9d0;
    }

    .site-signup {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .help-block {
        color: red;
    }
    .home {
        position: absolute;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
    }
");
?>
