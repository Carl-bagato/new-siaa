<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Flashcard Display';
$this->registerCssFile('@web/css/displayNoLogin.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve flashcards from session
$flashcards = $_SESSION['flashcards'] ?? null;
?>

<div class="container my-5">
    <div class="card shadow-lg border-0 p-4">
        <?php if (!$flashcards): ?>
            <div class="alert alert-danger">No flashcards found!</div>
        <?php else: ?>
            <h3 class="text-center mb-4"><?= Html::encode($flashcards['title']) ?></h3>
            <p class="text-center"><?= Html::encode($flashcards['description']) ?></p>

            <!-- Flashcard Container -->
            <div id="flashcards" class="flashcard-container">
                <div class="flashcard">
                    <div class="front">
                        <h3 id="flashcard-term"></h3>
                    </div>
                    <div class="back">
                        <p id="flashcard-definition"></p>
                    </div>
                </div>
            </div>

            <!-- Flashcard Navigation -->
            <div class="mt-4 text-center">
                <span id="term-number" class="me-3"></span>
                <button class="btn btn-secondary me-3" id="prev-btn" disabled>&laquo; Previous</button>
                <button class="btn btn-primary" id="flip-btn">Flip</button>
                <button class="btn btn-secondary ms-3" id="next-btn">Next &raquo;</button>
            </div>

            <!-- Logout link -->
            <a href="<?= Url::to(['site/close-flashcard']) ?>" class="btn btn-danger position-absolute top-0 end-0 m-3" id="close-btn">Close</a>
            <?php endif; ?>
    </div>
    
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // PHP data inserted into JavaScript
    const flashcards = <?php echo json_encode($flashcards['cards']); ?>;
    const flashcardCount = <?php echo count($flashcards['cards']); ?>;

    let currentIndex = 0;

    const termEl = document.getElementById("flashcard-term");
    const definitionEl = document.getElementById("flashcard-definition");
    const flipBtn = document.getElementById("flip-btn");
    const prevBtn = document.getElementById("prev-btn");
    const nextBtn = document.getElementById("next-btn");
    const flashcard = document.querySelector(".flashcard");
    const termNumberEl = document.getElementById("term-number");

    // Function to update the flashcard content
    function updateFlashcard() {
        termEl.textContent = flashcards[currentIndex].term;
        definitionEl.textContent = flashcards[currentIndex].definition;
        termNumberEl.textContent = `Term ${currentIndex + 1} of ${flashcardCount}`;

        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex === flashcards.length - 1;

        // Ensure the card is not flipped when moving to another card
        if (flashcard.classList.contains("flipped")) {
            flashcard.classList.remove("flipped");
        }
    }

    // Flip the flashcard when the flip button is clicked
    flipBtn.addEventListener("click", () => {
        flashcard.classList.toggle("flipped");
    });

    // Navigate to the previous flashcard
    prevBtn.addEventListener("click", () => {
        currentIndex--;
        updateFlashcard();
    });

    // Navigate to the next flashcard
    nextBtn.addEventListener("click", () => {
        currentIndex++;
        updateFlashcard();
    });

    // Initialize the first flashcard
    updateFlashcard();
});
</script>
