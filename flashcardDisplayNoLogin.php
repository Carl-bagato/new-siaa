<?php
session_start();

// Check if the flashcard set is available in the session
if (!isset($_SESSION['flashcard_set']) || empty($_SESSION['flashcard_set']['flashcards'])) {
    // If no flashcards are available, redirect to create page or show an error
    header('Location: createFlashcardNoLogin.php');
    exit;
}

// Get the flashcards from session
$flashcards = $_SESSION['flashcard_set']['flashcards'];
$title = $_SESSION['flashcard_set']['title']; // Title of the set
$description = $_SESSION['flashcard_set']['description']; // Description of the set
$flashcardCount = count($flashcards); // Total number of flashcards
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
    background-color: #dc3545; /* Bootstrap danger color */
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
    background-color: #c82333; /* Darker shade on hover */
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
    // PHP data inserted into JavaScript
    const flashcards = <?php echo json_encode($flashcards); ?>;
    const flashcardCount = <?php echo $flashcardCount; ?>;

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
        console.log("Current Index:", currentIndex);  // Debugging line
        console.log("Flashcard Term:", flashcards[currentIndex].term);  // Debugging line
        console.log("Flashcard Definition:", flashcards[currentIndex].definition);  // Debugging line
        
        termEl.textContent = flashcards[currentIndex].term;
        definitionEl.textContent = flashcards[currentIndex].definition;
        termNumberEl.textContent = `Term ${currentIndex + 1} of ${flashcardCount}`;

        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex === flashcards.length - 1;

        if (flashcard.classList.contains("flipped")) {
            flashcard.classList.remove("flipped");
        }
    }

    // Flip the flashcard when the flip button is clicked
    flipBtn.addEventListener("click", () => {
        flashcard.classList.toggle("flipped");
    });

    // Go to the previous flashcard
    prevBtn.addEventListener("click", () => {
        currentIndex--;
        updateFlashcard();
    });

    // Go to the next flashcard
    nextBtn.addEventListener("click", () => {
        currentIndex++;
        updateFlashcard();
    });

    // Initialize the first flashcard
    updateFlashcard();

    


</script>
</body>
</html>
