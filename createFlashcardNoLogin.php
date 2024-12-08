<?php
session_start();

// Handle form submission and save data to the session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save the flashcard set to the session
    $_SESSION['flashcard_set'] = [
        'title' => $_POST['setTitle'] ?? '',
        'description' => $_POST['setDescription'] ?? '',
        'flashcards' => $_POST['flashcards'] ?? []
    ];
    
    // Redirect to the display page after saving the data
    header('Location: flashcardDisplayNoLogin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Recall - Create Flashcard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="createFlashcard.css" rel="stylesheet">
</head>
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
    }

    .card {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 30px;
    }

    h3 {
        color: #333;
        font-weight: 600;
    }

    .card-item {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
    }

    .btn-secondary {
        color: #fefefe;
        background-color: #ffc23b;
        border: none;
    }

    .btn-secondary:hover {
        color: #fefefe;
        background-color: #E6A02E;
        border: none;
    }

    .btn-danger {
        color: #fefefe;
        background-color: #ff7254;
        text-decoration: none;
        border: none;
    }

    .btn-danger:hover {
        color: #fefefe;
        background-color: #CC5C44;
        text-decoration: none;
        border: none;
    }

    .btn-primary-submit {
        color: #fefefe;
        background-color: #086942;
        border: none;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary-submit:hover {
        color: #fefefe;
        background-color: #065533;
        transform: scale(1.02);
    }

    .btn-outline-success {
        color: #fefefe;
        background-color: #73d4eb;
        border: none;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-outline-success:hover {
        color: #fefefe;
        background-color: #58b9d0;
        border: none;
        transform: scale(1.02);
    }

</style>
<body>

<div class="container my-5">
    <div class="card shadow-lg border-0 p-4">
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-secondary" onclick="history.back()">Back</button>
            <button type="reset" class="btn btn-danger" form="flashcardForm">Close</button>
        </div>
        <h3 class="text-center mb-4">Create a new Flashcard Set</h3>
        <form id="flashcardForm" method="POST" action="">
            <div class="mb-3">
                <label for="setTitle" class="form-label fw-bold">Set Title</label>
                <input type="text" class="form-control" id="setTitle" name="setTitle" placeholder="Enter set title" required>
            </div>
            <div class="mb-3">
                <label for="setDescription" class="form-label fw-bold">Set Description</label>
                <textarea class="form-control" id="setDescription" name="setDescription" rows="3" placeholder="Enter a brief description" required></textarea>
            </div>
            <div id="flashcards"></div>
            <button type="button" class="btn btn-outline-success w-100 my-3" onclick="addCard()">Add More Card</button>
            <button type="submit" class="btn btn-primary-submit w-100">Create Flashcard Set</button>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                You need to log in or sign up to add more flashcards beyond the default limit.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="./loginPage.php" class="btn btn-primary">Log In</a>
            </div>
        </div>
    </div>
</div>

<script>
    let cardCounter = 10;
    const maxCards = 10;

    function initializeCards() {
        for (let i = 1; i <= 10; i++) {
            addCard(true, i);
        }
    }

    function addCard(initial = false, cardIndex = null) {
        const flashcardsDiv = document.getElementById("flashcards");

        if (!initial && cardCounter >= maxCards) {
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
            return;
        }

        const cardNumber = cardIndex || cardCounter + 1;

        const cardHTML = `
            <div class="card-item border p-3 mb-3 rounded" id="card${cardNumber}">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold">Card ${cardNumber}</h6>
                    <button class="btn btn-danger btn-sm" onclick="deleteCard(${cardNumber})">Delete</button>
                </div>
                <div class="d-flex gap-3">
                    <div class="mb-3 flex-grow-1">
                        <label for="term${cardNumber}" class="form-label fw-bold">Term</label>
                        <input type="text" class="form-control" id="term${cardNumber}" name="flashcards[${cardNumber}][term]" placeholder="Enter term" required>
                    </div>
                    <div class="mb-3 flex-grow-1">
                        <label for="definition${cardNumber}" class="form-label fw-bold">Definition</label>
                        <textarea class="form-control" id="definition${cardNumber}" name="flashcards[${cardNumber}][definition]" rows="2" placeholder="Enter definition" required></textarea>
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
            cardCounter--;
        }
    }

    document.addEventListener("DOMContentLoaded", initializeCards);
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

