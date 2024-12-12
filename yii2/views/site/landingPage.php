<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;

$this->title = 'Quick Recall';

// Register your custom CSS for landing page, if needed
$this->registerCssFile('@web/css/landingPage.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);

// Check if the user is logged in, using Yii's user component
if (Yii::$app->user->isGuest) {
    // If user is not logged in, redirect to the login page
    Yii::$app->response->redirect(['site/loginPage']);
    exit;
}
?>

<!-- Navbar Section -->
<?php
NavBar::begin([
    'brandLabel' => Html::img('@web/images/quick_recall_logo.png', ['alt' => 'Quick Recall Logo', 'style' => 'height: 25px;']),
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar navbar-expand-lg bg-light border-bottom'],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav ms-auto'],
    'items' => [
        ['label' => 'Home', 'url' => '#hero-section', 'linkOptions' => ['class' => 'nav-link']],
        ['label' => 'About Us', 'url' => '#about', 'linkOptions' => ['class' => 'nav-link']],
        ['label' => 'Features', 'url' => '#features', 'linkOptions' => ['class' => 'nav-link']],
        ['label' => 'Contact', 'url' => '#contact', 'linkOptions' => ['class' => 'nav-link']],
        ['label' => 'Logout', 'url' => ['site/logout'], 'linkOptions' => ['class' => 'btn btn-danger']],
    ],
]);
NavBar::end();
?>

<!-- Hero Section -->
<section id="hero-section" class="text-center py-6">
    <div class="container">
        <h1><span class="highlight-hero">Master</span> Anything, Anytime</h1>
        <p class="lead my-4">
            Unlock your full learning potential with Quick Recall. Create, organize, and review flashcards effortlessly on any device. Whether you're preparing for exams, learning a new language, or mastering new skills helps boost your retention and achieve your goals faster.
        </p>
        <a href="<?= Url::to(['flashcard-dashboard/index']) ?>" class="btn btn-lg custom-btn-hero">Go To Dashboard</a>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-6 text-center">
    <div class="container">
        <h1 class="mb-4">What is <span class="highlight-about">Quick Recall?</span></h1>
        <p class="mb-3">At Quick Recall, we're on a mission to revolutionize the way you learn and retain information. Our flashcard app is designed to help you study smarter, not harder, by providing a streamlined and user-friendly experience for creating, organizing, and reviewing flashcards.</p>
        <p class="mb-0">Whether you're preparing for exams, learning a new language, or mastering a new skill, Quick Recall offers the tools to make your study sessions more efficient and effective.</p>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-6">
    <div class="container">
        <div class="row text-center">
            <h1 class="mb-5">Discover the <span class="highlight-features">Power</span> of Quick Recall</h1>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h3 class="card-title">Create Flashcards</h3>
                        <p class="card-text">Easily create flashcards on any topic and organize them as needed.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h3 class="card-title">Quick Recall Mode</h3>
                        <p class="card-text">Review and test your knowledge with our quick recall mode for faster learning.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h3 class="card-title">Track Progress</h3>
                        <p class="card-text">Monitor your learning progress with built-in stats and analytics.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-6 text-center">
    <div class="container">
        <h2 class="mb-4">Contact Us</h2>
        <p class="mb-4">If you have any questions or feedback, feel free to reach out to us.</p>
        <form>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <input type="text" class="form-control mb-3" placeholder="Name" required>
                    <input type="email" class="form-control mb-3" placeholder="Email" required>
                    <textarea class="form-control mb-3" rows="5" placeholder="Message" required></textarea>
                    <button type="submit" class="btn btn-contact w-100">Send Message</button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Footer -->
<footer class="footer py-3 text-center">
    <p class="mb-0">© 2024 Quick Recall. All rights reserved.</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
