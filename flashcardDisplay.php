<?php
session_start(); // Start the session

// Check if both the user_name and user_id are set in the session
if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: loginPage.php');
    exit;
}

echo "<h1>Welcome to the Landing Page, " . htmlspecialchars($_SESSION['user_name']) . "!</h1>";

$loggedInUserId = $_SESSION['user_id']; // Get the logged-in user's ID
echo "<h1>Welcome to the Landing Page, " . htmlspecialchars($_SESSION['user_id']) . "!</h1>";

require_once 'db_config.php';

// Check if the flashcard_id is provided in the URL
if (!isset($_GET['flashcard_id'])) {
    die("No flashcard set found.");
}

$flashcard_id = $_GET['flashcard_id'];

// Fetch the flashcard set and its terms from the database
try {
    // Fetch flashcard set (title and content) using the correct column names
    $stmt = $pdo->prepare("SELECT title, content FROM flashcard WHERE flashcard_id = ?");
    $stmt->execute([$flashcard_id]);
    $flashcard = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$flashcard) {
        die("Flashcard set not found with ID: " . htmlspecialchars($flashcard_id));
    }

    // Fetch terms and definitions
    $stmt = $pdo->prepare("SELECT term, answer FROM term_answer WHERE flashcard_id = ?");
    $stmt->execute([$flashcard_id]);
    $terms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($terms)) {
        die("No terms found for flashcard ID: " . htmlspecialchars($flashcard_id));
    }

} catch (Exception $e) {
    die("Error fetching flashcards: " . $e->getMessage());
}

$title = $flashcard['title'];
$content = $flashcard['content']; 
$description = $content;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Recall - Flashcard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>

body {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 0;
    background-color: #f9f9f9;
    font-family: 'Inter', sans-serif;
}

.container {
    position: relative;
    text-align: center;
}

#close-btn {
    background-color: #dc3545; 
    border: none;
    font-size: 1rem;
    color: white;
    cursor: pointer;
    position: absolute;
    top: 6px;
    right: 10px;
    padding: 0.5rem 1rem;
    border-radius: 5px;
}

#close-btn:hover {
    background-color: #c82333; 
}

.flashcard-container {
    perspective: 1500px;
}

.flashcard {
    width: 600px;
    height: 400px;
    border: 2px solid #ccc;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.8s ease;
    cursor: pointer;
}

.flashcard .front,
.flashcard .back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 1.5rem;
    font-weight: bold;
}

.flashcard .front {
    background: #fefefe;
    color: #262626;
}

.flashcard .back {
    background: #262626;
    color: #fefefe;
    transform: rotateY(180deg);
}

.flashcard.flipped {
    transform: rotateY(180deg);
}

button {
    font-size: 1.2rem;
    padding: 0.6rem 1.5rem;
}

.btn-secondary {
    color: #fefefe;
    background-color: #ff7254;
    text-decoration: none;
    border: none;
}

.btn-secondary:hover {
    color: #fefefe;
    background-color: #CC5C44;
    text-decoration: none;
    border: none;
}

.btn-primary {
    color: #fefefe;
    background-color: #ffc23b;
    border: none;
}

.btn-primary:hover {
    color: #fefefe;
    background-color: #E6A02E;
    border: none;
}

.title {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.description {
    font-size: 1.2rem;
    margin-bottom: 20px;
}
</style>

<body>
    <div class="container d-flex flex-column align-items-center mt-5">
        <button class="btn btn-danger position-absolute top-0 end-0 m-3" id="close-btn">Close</button>
        <!-- Title and Description -->
        <div class="title"><?php echo htmlspecialchars($title); ?></div>
        <div class="description"><?php echo htmlspecialchars($description); ?></div>
        
        <!-- Flashcard Container -->
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

        <!-- Flashcard Navigation -->
        <div class="mt-4">
            <span id="term-number" class="me-3"></span>
            <button class="btn btn-secondary me-3" id="prev-btn" disabled>&laquo; Previous</button>
            <button class="btn btn-primary" id="flip-btn">Flip</button>
            <button class="btn btn-secondary ms-3" id="next-btn">Next &raquo;</button>
        </div>
    </div>

<script>
// Fetching flashcards data from PHP
const flashcards = <?php echo json_encode($terms); ?>;
const flashcardCount = flashcards.length;

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
    if (currentIndex < 0 || currentIndex >= flashcards.length) {
        console.error("Invalid flashcard index:", currentIndex);
        return; // Prevents trying to display a non-existing flashcard
    }

    termEl.textContent = flashcards[currentIndex].term;
    definitionEl.textContent = flashcards[currentIndex].answer;
    termNumberEl.textContent = `Term ${currentIndex + 1} of ${flashcardCount}`;

    prevBtn.disabled = currentIndex === 0;
    nextBtn.disabled = currentIndex === flashcards.length - 1;

    if (flashcard.classList.contains("flipped")) {
        flashcard.classList.remove("flipped");
    }
}

// Flip the flashcard when the flip button is clicked
flipBtn.addEventListener("click", () => flashcard.classList.toggle("flipped"));

// Go to the previous flashcard
prevBtn.addEventListener("click", () => { currentIndex--; updateFlashcard(); });

// Go to the next flashcard
nextBtn.addEventListener("click", () => { currentIndex++; updateFlashcard(); });

document.addEventListener("DOMContentLoaded", function() {
        const closeBtn = document.getElementById("close-btn");
        if (closeBtn) {
            closeBtn.addEventListener("click", function() {
                console.log("Close button clicked");  // Debugging log to check if the button is clicked
                window.location.href = "logoutNoLogin.php"; 
            });
        } else {
            console.log("Close button not found");
        }
    });
// Initialize the first flashcard
updateFlashcard();
    </script>
</body>
</html>