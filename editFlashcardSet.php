<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Recall - Edit Flashcard Set</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="createFlashcard.css" rel="stylesheet">
</head>

<style>

    
</style>
<body>

<div class="container my-5">
    <div class="card shadow-lg border-0 p-4">
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-secondary" onclick="history.back()">Back</button>
            <button type="reset" class="btn btn-danger" form="flashcardForm">Close</button>
        </div>
        <h3 class="text-center mb-4">Edit Flashcard Set</h3>
        <form id="flashcardForm" onsubmit="validateForm(event)">
            <div class="mb-3">
                <label for="setTitle" class="form-label fw-bold">Set Title</label>
                <input type="text" class="form-control" id="setTitle" value="Sample Flashcard Set" required>
            </div>
            <div class="mb-3">
                <label for="setDescription" class="form-label fw-bold">Set Description</label>
                <textarea class="form-control" id="setDescription" rows="3" required>Sample description for editing</textarea>
            </div>
            <div id="flashcards">
                <!-- Pre-filled Flashcard Items Here -->
                <div class="card-item border p-3 mb-3 rounded">
                    <h6 class="fw-bold">Card 1</h6>
                    <div class="d-flex gap-3">
                        <div class="mb-3 flex-grow-1">
                            <label for="term1" class="form-label fw-bold">Term</label>
                            <input type="text" class="form-control" id="term1" value="Term 1" required>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="definition1" class="form-label fw-bold">Definition</label>
                            <textarea class="form-control" id="definition1" rows="2" required>Definition for Term 1</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-item border p-3 mb-3 rounded">
                    <h6 class="fw-bold">Card 2</h6>
                    <div class="d-flex gap-3">
                        <div class="mb-3 flex-grow-1">
                            <label for="term2" class="form-label fw-bold">Term</label>
                            <input type="text" class="form-control" id="term2" value="Term 2" required>
                        </div>
                        <div class="mb-3 flex-grow-1">
                            <label for="definition2" class="form-label fw-bold">Definition</label>
                            <textarea class="form-control" id="definition2" rows="2" required>Definition for Term 2</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary-submit w-100">Save Changes</button>
        </form>
    </div>
</div>

<script>
    let cardCounter = 2;

    function addCard() {
        cardCounter++;
        const flashcardsDiv = document.getElementById("flashcards");
        const cardHTML = `
            <div class="card-item border p-3 mb-3 rounded">
                <h6 class="fw-bold">Card ${cardCounter}</h6>
                <div class="d-flex gap-3">
                    <div class="mb-3 flex-grow-1">
                        <label for="term${cardCounter}" class="form-label fw-bold">Term</label>
                        <input type="text" class="form-control" id="term${cardCounter}" placeholder="Enter term" required>
                    </div>
                    <div class="mb-3 flex-grow-1">
                        <label for="definition${cardCounter}" class="form-label fw-bold">Definition</label>
                        <textarea class="form-control" id="definition${cardCounter}" rows="2" placeholder="Enter definition" required></textarea>
                    </div>
                </div>
            </div>
        `;
        flashcardsDiv.insertAdjacentHTML("beforeend", cardHTML);
    }

    function validateForm(event) {
        const inputs = document.querySelectorAll('input[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            alert("Please fill out all fields before submitting.");
            event.preventDefault();
        }
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
