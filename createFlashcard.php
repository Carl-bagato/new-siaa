<?php
session_start();
require_once 'db_config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    die("Access denied. Please log in first.");
}

$user_id = $_SESSION['user_id']; // Retrieve the logged-in user's ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $set_title = trim($_POST['set_title']);
    $set_description = trim($_POST['set_description']);

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Insert the flashcard set into the flashcard table
        $stmt = $pdo->prepare("INSERT INTO flashcard (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $set_title, $set_description]);

        $flashcard_id = $pdo->lastInsertId();  // Get the ID of the newly inserted flashcard set

        // Insert terms and definitions into the term_answer table
        if (isset($_POST['terms']) && isset($_POST['definitions'])) {
            $terms = $_POST['terms'];
            $definitions = $_POST['definitions'];

            $stmt = $pdo->prepare("INSERT INTO term_answer (flashcard_id, term, answer) VALUES (?, ?, ?)");
            for ($i = 0; $i < count($terms); $i++) {
                $term = trim($terms[$i]);
                $definition = trim($definitions[$i]);
                if (!empty($term) && !empty($definition)) {
                    $stmt->execute([$flashcard_id, $term, $definition]);
                }
            }
        }

        $pdo->commit();

        // Redirect to the flashcardDisplay.php page and pass the flashcard_id
        echo "<script>alert('Flashcard set saved successfully!'); window.location.href='flashcardDisplay.php?flashcard_id=" . $flashcard_id . "';</script>";
    } catch (Exception $e) {
        // Rollback transaction if there's an error
        $pdo->rollBack();
        echo "<script>alert('Failed to save flashcard set: " . $e->getMessage() . "');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Recall - Create Flashcard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="createFlashcard.css" rel="stylesheet"> -->
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

label.form-label {
    font-weight: bold;
}

input.form-control, textarea.form-control {
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 1rem;
    padding: 10px;
    transition: border-color 0.3s;
}

.is-invalid {
    border-color: #dc3545;
}

.d-flex {
    display: flex;
    align-items: flex-start;
}

.gap-3 {
    gap: 1rem;
}

.btn-secondary{
    color: #fefefe;
    background-color: #ffc23b;
    border: none;
}

.btn-secondary:hover{
    color: #fefefe;
    background-color: #E6A02E;
    border: none;
}

.btn-danger{
    color: #fefefe; 
    background-color: #ff7254;
    text-decoration: none;
    border: none;
}

.btn-danger:hover{
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

.btn-outline-success{
    color: #fefefe;
    background-color: #73d4eb;
    border: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-outline-success:hover{
    color: #fefefe;
    background-color: #58b9d0;
    border: none;
    transform: scale(1.02);
}

/* 
textarea,
input {
    height: calc(2.5em + 0.75rem + 2px); 
    resize: none;
} */
</style>

<body>

<div class="container my-5">
    <div class="card shadow-lg border-0 p-4">
        <h3 class="text-center mb-4">Create a new Flashcard Set</h3>

        <form method="POST" id="flashcardForm">
            <div class="mb-3">
                <label for="setTitle" class="form-label fw-bold">Set Title</label>
                <input type="text" name="set_title" class="form-control" id="setTitle" placeholder="Enter set title" required>
            </div>
            <div class="mb-3">
                <label for="setDescription" class="form-label fw-bold">Set Description</label>
                <textarea name="set_description" class="form-control" id="setDescription" rows="3" placeholder="Enter a brief description" required></textarea>
            </div>
            <div id="flashcards"></div>

            <button type="button" class="btn btn-outline-success w-100 my-3" onclick="addCard()">Add More Card</button>
            <button type="submit" class="btn btn-primary-submit w-100">Create Flashcard Set</button>
        </form>
    </div>
</div>

<script>
    let cardCounter = 1;

    function addCard() {
        const flashcardsDiv = document.getElementById("flashcards");

        const cardHTML = `
            <div class="card-item border p-3 mb-3 rounded" id="card${cardCounter}">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold">Card ${cardCounter}</h6>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteCard(${cardCounter})">Delete</button>
                </div>
                <div class="d-flex gap-3">
                    <div class="mb-3 flex-grow-1">
                        <label for="term${cardCounter}" class="form-label fw-bold">Term</label>
                        <input type="text" name="terms[]" class="form-control" id="term${cardCounter}" placeholder="Enter term" required>
                    </div>
                    <div class="mb-3 flex-grow-1">
                        <label for="definition${cardCounter}" class="form-label fw-bold">Definition</label>
                        <textarea name="definitions[]" class="form-control" id="definition${cardCounter}" rows="2" placeholder="Enter definition" required></textarea>
                    </div>
                </div>
            </div>
        `;

        flashcardsDiv.insertAdjacentHTML("beforeend", cardHTML);
        cardCounter++;
    }

    function deleteCard(cardNumber) {
        const cardElement = document.getElementById(`card${cardNumber}`);
        if (cardElement) {
            cardElement.remove();
        }
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>