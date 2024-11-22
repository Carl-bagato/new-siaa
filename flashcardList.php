<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Recall  List</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="flashcardList.css" rel="stylesheet"> -->
</head>
<style>
    /* General Styles */
body {
  font-family: 'Arial', sans-serif;
  color: #262626;
  background-color: #f9f9f9;
}

.container {
  max-width: 900px;
}

/* Card Styling */
.card {
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.card h3 {
  font-size: 24px;
  color: #262626;
}

.list-group-item {
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-bottom: 10px;
  background-color: #fafafa;
}

.list-group-item:hover {
  background-color: #f1f1f1;
}

/* Button Styling */
.btn {
  border-radius: 5px;
}

.btn-primary {
  color: #fefefe;
  background-color: #086942;
  border: none;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-primary:hover {
  color: #fefefe;
  background-color: #065533;
  transform: scale(1.02);
}

.btn-warning {
  color: #fefefe;
  background-color: #ffc23b;
  border: none;
}

.btn-warning:hover {
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

/* Flashcard Set Styling */
.flashcard-set-item {
  font-weight: bold;
  font-size: 18px;
}

.flashcard-set-item p {
  font-size: 14px;
  color: #666;
}

.flashcard-set-item .btn {
  margin-left: 10px;
}

</style>


<body>

<div class="container my-5">
    <div class="card shadow-lg border-0 p-4">
        <h3 class="text-center mb-4">Flashcard Sets</h3>
        <div id="flashcardList" class="list-group">
        </div>
        <div class="text-center mt-3">
            <button class="btn btn-primary" onclick="addFlashcardSet()">Add New Flashcard Set</button>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
const flashcardSets = [
    {
        id: 1,
        title: "Set 1: Programming Basics",
        description: "A set of flashcards to cover the basics of programming including variables, loops, and functions.",
        cards: [
            { term: "Variable", definition: "A container for storing data values." },
            { term: "Function", definition: "A block of code designed to perform a particular task." },
        ]
    },
    {
        id: 2,
        title: "Set 2: HTML & CSS",
        description: "Flashcards covering the fundamentals of HTML and CSS.",
        cards: [
            { term: "HTML", definition: "The standard markup language for documents designed to be displayed in a web browser." },
            { term: "CSS", definition: "A style sheet language used for describing the presentation of a document written in HTML." },
        ]
    }
];

function renderFlashcardSets() {
    const flashcardList = document.getElementById('flashcardList');
    flashcardList.innerHTML = ''; 

    flashcardSets.forEach(set => {
        const listItem = document.createElement('div');
        listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
        
        listItem.innerHTML = `
            <div>
                <h5>${set.title}</h5>
                <p>${set.description}</p>
            </div>
            <div>
                <button class="btn btn-warning btn-sm" onclick="editFlashcardSet(${set.id})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteFlashcardSet(${set.id})">Delete</button>
            </div>
        `;

        flashcardList.appendChild(listItem);
    });
}

function addFlashcardSet() {
    const newSet = {
        id: flashcardSets.length + 1,
        title: "Set " + (flashcardSets.length + 1),
        description: "Description for new set",
        cards: []
    };
    flashcardSets.push(newSet);
    renderFlashcardSets();
}

function editFlashcardSet(setId) {
    const set = flashcardSets.find(f => f.id === setId);

    document.getElementById('editSetTitle').value = set.title;
    document.getElementById('editSetDescription').value = set.description;
    
    document.getElementById('editModal').dataset.setId = setId;

    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}

function updateFlashcardSet() {
    const setId = document.getElementById('editModal').dataset.setId;
    const set = flashcardSets.find(f => f.id === parseInt(setId));

    set.title = document.getElementById('editSetTitle').value;
    set.description = document.getElementById('editSetDescription').value;

    renderFlashcardSets();

    const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
    modal.hide();
}

function deleteFlashcardSet(setId) {
    const index = flashcardSets.findIndex(f => f.id === setId);
    if (index !== -1) {
        flashcardSets.splice(index, 1); 
        renderFlashcardSets(); 
    }
}

renderFlashcardSets();
</script>

</body>
</html>
