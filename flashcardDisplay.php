<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>>Quick Recall - Flashcard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="flashcardDisplay.css"> -->
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

.container{
    position: relative;
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

.btn-secondary{ 
    color: #fefefe; 
    background-color: #ff7254;
    text-decoration: none;
    border: none;
}

.btn-secondary:hover{
    color: #fefefe;
    background-color: #CC5C44;
    text-decoration: none;
    border: none;
}

.btn-primary{
    color: #fefefe;
    background-color: #ffc23b;
    border: none;
}

.btn-primary:hover{
    color: #fefefe;
    background-color: #E6A02E;
    border: none;
}



</style>

<body>
    <div class="container d-flex flex-column align-items-center mt-5">
        <button class="btn btn-danger position-absolute top-0 end-0 m-3" id="close-btn">Close</button>
        <div class="flashcard-container">
            <div class="flashcard">
                <div class="front">
                <h3 id="flashcard-term">Term 1</h3>
                </div>
                <div class="back">
                <p id="flashcard-definition">Definition 1</p>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <button class="btn btn-secondary me-3" id="prev-btn" disabled>&laquo; Previous</button>
            <button class="btn btn-primary" id="flip-btn">Flip</button>
            <button class="btn btn-secondary ms-3" id="next-btn">Next &raquo;</button>
        </div>
    </div>


<!-- <script src="flashcardDisplay.js"></script> -->
<script>
    const flashcards = [
    { term: "Term 1", definition: "Definition 1" },
    { term: "Term 2", definition: "Definition 2" },
    { term: "Term 3", definition: "Definition 3" },
    { term: "Term 4", definition: "Definition 4" },
    { term: "Term 5", definition: "Definition 5" }
];

let currentIndex = 0;

const termEl = document.getElementById("flashcard-term");
const definitionEl = document.getElementById("flashcard-definition");
const flipBtn = document.getElementById("flip-btn");
const prevBtn = document.getElementById("prev-btn");
const nextBtn = document.getElementById("next-btn");
const flashcard = document.querySelector(".flashcard");

function updateFlashcard() {
    termEl.textContent = flashcards[currentIndex].term;
    definitionEl.textContent = flashcards[currentIndex].definition;

    prevBtn.disabled = currentIndex === 0;
    nextBtn.disabled = currentIndex === flashcards.length - 1;

    if (flashcard.classList.contains("flipped")) {
        flashcard.classList.remove("flipped");
    }
}

flipBtn.addEventListener("click", () => {
    flashcard.classList.toggle("flipped");
});

prevBtn.addEventListener("click", () => {
    currentIndex--;
    updateFlashcard();
});

nextBtn.addEventListener("click", () => {
    currentIndex++;
    updateFlashcard();
});

updateFlashcard();




</script>
</body>
</html>
