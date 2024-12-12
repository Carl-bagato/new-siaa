<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Create Flashcard Set';
$this->registerCssFile('@web/css/createNoLogin.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
$this->registerJsFile('@web/js/bootstrap.bundle.min.js', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', [
    'depends' => [\yii\web\JqueryAsset::class], // Optional, only if using jQuery
    'position' => \yii\web\View::POS_END,
]);

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect title, description, and flashcards
    $title = $_POST['setTitle'] ?? 'Untitled';
    $description = $_POST['setDescription'] ?? '';
    $flashcards = $_POST['flashcards'] ?? [];

    // Store in session
    $_SESSION['flashcards'] = [
        'title' => $title,
        'description' => $description,
        'cards' => $flashcards,
    ];

    // Redirect to display page
    header('Location: display-flashcard.php');
    exit;
}

?>

<div class="container my-5">
    <div class="card shadow-lg border-0 p-4">
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-secondary" onclick="history.back()">Back</button>
            <button type="reset" class="btn btn-danger" form="flashcardForm" onclick="resetCards()">Reset</button>
        </div>
        <h3 class="text-center mb-4"><?= Html::encode($this->title) ?></h3>

        <form id="flashcardForm" method="post">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
            <!-- Set Title and Description -->
            <div class="mb-3">
                <label for="setTitle" class="form-label fw-bold">Set Title</label>
                <input type="text" id="setTitle" name="setTitle" class="form-control" placeholder="Enter set title" required>
            </div>
            <div class="mb-3">
                <label for="setDescription" class="form-label fw-bold">Set Description</label>
                <textarea id="setDescription" name="setDescription" class="form-control" rows="3" placeholder="Enter a brief description" required></textarea>
            </div>
            <!-- Flashcards Section -->
            <div id="flashcards"></div>
            <button type="button" class="btn btn-outline-success w-100 my-3" onclick="addCard()">Add More Card</button>
            <button type="submit" class="btn btn-primary-submit w-100">Create Flashcard Set</button>
        </form>

    </div>

        <!-- Modal for login prompt -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Limit Reached</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You can only add up to 10 flashcards per set. Please remove an existing card if you want to add a new one.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <?= Html::a('Log In', Url::to(['site/login']), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
let cardCounter = 10;  // Start with 5 cards
const maxCards = 10;  // Total limit of 10 cards

function initializeCards() {
    for (let i = 1; i <= cardCounter; i++) {
        addCard(true, i);
    }
}

function addCard(initial = false, cardIndex = null) {
    const flashcardsDiv = document.getElementById("flashcards");

    if (!initial && cardCounter >= maxCards) {
        // Show the modal if the card limit is reached
        const modal = new bootstrap.Modal(document.getElementById('loginModal'));
        modal.show();
        return;
    }

    const cardNumber = cardIndex || cardCounter + 1;

    const cardHTML = `
        <div class="card-item border p-3 mb-3 rounded" id="card${cardNumber}">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="fw-bold">Card ${cardNumber}</h6>
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteCard(${cardNumber})">Delete</button>
            </div>
            <div class="d-flex gap-3">
                <div class="mb-3 flex-grow-1">
                    <label for="term${cardNumber}" class="form-label fw-bold">Term</label>
                    <input type="text" id="term${cardNumber}" name="flashcards[${cardNumber}][term]" class="form-control" placeholder="Enter term" required>
                </div>
                <div class="mb-3 flex-grow-1">
                    <label for="definition${cardNumber}" class="form-label fw-bold">Definition</label>
                    <textarea id="definition${cardNumber}" name="flashcards[${cardNumber}][definition]" class="form-control" rows="2" placeholder="Enter definition" required></textarea>
                </div>
            </div>
        </div>
    `;

    flashcardsDiv.insertAdjacentHTML("beforeend", cardHTML);

    if (!initial) {
        cardCounter++;
    }
}

function deleteCard(cardNumber) {
    const cardElement = document.getElementById(`card${cardNumber}`);
    if (cardElement) {
        cardElement.remove();
        cardCounter = Math.max(cardCounter - 1, 0); // Prevent negative cardCounter
    }
}

function resetCards() {
    const flashcardsDiv = document.getElementById("flashcards");
    flashcardsDiv.innerHTML = '';
    cardCounter = 5;
    initializeCards();
}

document.addEventListener("DOMContentLoaded", initializeCards);
</script>
