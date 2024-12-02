
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
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-secondary" onclick="history.back()">Back</button>
            <button type="reset" class="btn btn-danger" form="flashcardForm">Close</button>
        </div>
        <h3 class="text-center mb-4">Create a new Flashcard Set</h3>

        <form id="flashcardForm" onsubmit="prepareData(event)">
            <!-- Remove method="POST" and action="save_flashcard.php" -->
            <div class="mb-3">
                <label for="setTitle" class="form-label fw-bold">Set Title</label>
                <input type="text" class="form-control" id="setTitle" placeholder="Enter set title" required>
            </div>
            <div class="mb-3">
                <label for="setDescription" class="form-label fw-bold">Set Description</label>
                <textarea class="form-control" id="setDescription" rows="3" placeholder="Enter a brief description" required></textarea>
            </div>
            <div id="flashcards"></div>
            <button type="button" class="btn btn-outline-success w-100 my-3" onclick="addCard()">Add More Card</button>
            <button type="submit" class="btn btn-primary-submit w-100">Create Flashcard Set</button>
        </form>

    </div>
</div>

<script>
    let cardCounter = 5;

    function initializeCards() {
        for (let i = 1; i <= 5; i++) {
            addCard(true, i); 
        }
    }

    // Function to add a new card
    function addCard(initial = false, cardIndex = null) {
        const flashcardsDiv = document.getElementById("flashcards");
        const cardNumber = cardIndex || cardCounter + 1; 

        const cardHTML = `
            <div class="card-item border p-3 mb-3 rounded">
                <h6 class="fw-bold">Card ${cardNumber}</h6>
                <div class="d-flex gap-3">
                    <div class="mb-3 flex-grow-1">
                        <label for="term${cardNumber}" class="form-label fw-bold">Term</label>
                        <input type="text" class="form-control term-input" id="term${cardNumber}" placeholder="Enter term" required>
                    </div>
                    <div class="mb-3 flex-grow-1">
                        <label for="definition${cardNumber}" class="form-label fw-bold">Definition</label>
                        <textarea class="form-control definition-input" id="definition${cardNumber}" rows="2" placeholder="Enter definition" required></textarea>
                    </div>
                </div>
            </div>
        `;
        flashcardsDiv.insertAdjacentHTML("beforeend", cardHTML);

        if (!initial) {
            cardCounter++; 
        }
    }

    // Prepare JSON payload before submission
    function prepareData(event) {
    event.preventDefault(); // Prevent default form submission

    const setTitle = document.getElementById("setTitle").value.trim();
    const setDescription = document.getElementById("setDescription").value.trim();

    const terms = document.querySelectorAll('.term-input');
    const definitions = document.querySelectorAll('.definition-input');

    const flashcards = [];
    terms.forEach((term, index) => {
        const definition = definitions[index];
        if (term.value.trim() && definition.value.trim()) {
            flashcards.push({ term: term.value.trim(), definition: definition.value.trim() });
        }
    });

    const payload = {
        user_id: 1, // Replace with actual user ID logic
        set_title: setTitle,
        set_description: setDescription,
        flashcards: flashcards
    };

        // Send POST request with JSON payload using fetch
        fetch('save_flashcard.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ flashcardData: payload })
    })
    .then(response => response.text()) // Get the raw response text first
    .then(text => {
        console.log('Raw response:', text); // Log the raw response for debugging
        return JSON.parse(text); // Try to parse it as JSON
    })
    .then(data => {
        console.log(data); // Handle success or error response here
        if (data.status === 'success') {
            alert('Flashcard set saved successfully');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error submitting the form: ' + error.message);
    });

}



    // Validate form fields
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

    document.addEventListener("DOMContentLoaded", initializeCards);
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>