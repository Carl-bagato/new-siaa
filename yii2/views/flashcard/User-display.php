<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Flashcard $flashcard */
/** @var array $terms */

$this->title = 'Flashcard Set: ' . Html::encode($flashcard->title);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');
$this->registerCssFile('@web/css/userDisplay.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);

?>

<div class="container my-5">
    <div class="card shadow-lg border-0 p-4">
        <div class="d-flex justify-content-between mb-4">
            <a href="<?= Url::to(['flashcard/update', 'id' => $flashcard->flashcard_id]) ?>" class="btn btn-secondary">Edit</a>
            <a href="<?= Url::to(['flashcard/index']) ?>" class="btn btn-danger position-absolute top-0 end-0 m-4" id="close-btn">Close</a>
        </div>

        <h3 class="text-center mb-4"><?= Html::encode($flashcard->title) ?></h3>
        <p class="text-muted text-center"><?= Html::encode($flashcard->content) ?></p>

        <div class="flashcard-container">
            <div class="flashcard">
                <div class="front">
                    <h3 id="flashcard-term"></h3>
                </div>
                <div class="back">
                    <p id="flashcard-definition"></p>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <span id="term-number" class="me-3"></span>
            <button class="btn btn-secondary me-3" id="prev-btn" disabled>&laquo; Previous</button>
            <button class="btn btn-primary" id="flip-btn">Flip</button>
            <button class="btn btn-secondary ms-3" id="next-btn">Next &raquo;</button>
        </div>
    </div>
</div>

<script>
    const flashcards = <?= json_encode($terms) ?>; // Passing the fetched terms and answers
    const flashcardCount = flashcards.length;
    let currentIndex = 0;

    const termEl = document.getElementById("flashcard-term");
    const definitionEl = document.getElementById("flashcard-definition");
    const flipBtn = document.getElementById("flip-btn");
    const prevBtn = document.getElementById("prev-btn");
    const nextBtn = document.getElementById("next-btn");
    const flashcard = document.querySelector(".flashcard");
    const termNumberEl = document.getElementById("term-number");

    function updateFlashcard() {
        if (currentIndex < 0 || currentIndex >= flashcards.length) {
            console.error("Invalid flashcard index:", currentIndex);
            return;
        }

        termEl.textContent = flashcards[currentIndex].term;
        definitionEl.textContent = flashcards[currentIndex].answer;
        termNumberEl.textContent = 'Term ' + (currentIndex + 1) + ' of ' + flashcardCount;

        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex === flashcards.length - 1;

        if (flashcard.classList.contains("flipped")) {
            flashcard.classList.remove("flipped");
        }
    }

    flipBtn.addEventListener("click", () => flashcard.classList.toggle("flipped"));
    prevBtn.addEventListener("click", () => { currentIndex--; updateFlashcard(); });
    nextBtn.addEventListener("click", () => { currentIndex++; updateFlashcard(); });

    document.addEventListener("DOMContentLoaded", function() {
        const closeBtn = document.getElementById("close-btn");
        if (closeBtn) {
            closeBtn.addEventListener("click", function() {
                window.location.href = "flashcard/index";
            });
        }
    });

    updateFlashcard();
</script>
