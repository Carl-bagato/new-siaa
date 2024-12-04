<?php

$host = 'localhost';
$dbname = 'siaadatabase';
$username = 'root';
$password = '1802';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Replace mock user ID with session logic in real use
session_start();
$loggedInUserId = $_SESSION['user_id'] ?? 1;

// Fetch flashcards for the logged-in user
$query = "SELECT flashcard_id, title, content, date_created FROM flashcard WHERE user_id = :user_id ORDER BY date_created DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $loggedInUserId, PDO::PARAM_INT);
$stmt->execute();
$flashcards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


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
        <h1 class="text-center">Your Flashcards</h1>
        <div id="flashcardList">
            <?php if (count($flashcards) > 0): ?>
                <?php foreach ($flashcards as $flashcard): ?>
                  <div class="card shadow-lg border-0 p-4" data-id="<?= $flashcard['flashcard_id'] ?>">
                        <h3 class="text-center mb-4"><?= htmlspecialchars($flashcard['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($flashcard['content'])) ?></p>
                        <small class="text-muted">Created on: <?= htmlspecialchars($flashcard['date_created']) ?></small>
                        <div class="text-center mt-3">
                            <a href="./flashcardDisplay.php" class="btn btn-secondary btn-sm edit-btn" >Display</a>
                            <a href="./editFlashcardSet.php" class="btn btn-warning btn-sm edit-btn">Edit</a>
                            <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    No flashcards found. Start creating some!
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-3">
            <a href="createFlashcard.php" class="btn btn-primary">Add New Flashcard Set</a>
        </div>
    </div>

    <!-- Modal for Editing Flashcard -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Flashcard</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFlashcardForm">
                        <input type="hidden" id="flashcardId">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="editContent" class="form-label">Content</label>
                            <textarea class="form-control" id="editContent" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Handle Edit Button Click
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const card = e.target.closest('.card');
                    const id = card.getAttribute('data-id');
                    const title = card.querySelector('h3').innerText;
                    const content = card.querySelector('p').innerText;

                    document.getElementById('flashcardId').value = id;
                    document.getElementById('editTitle').value = title;
                    document.getElementById('editContent').value = content;

                    const modal = new bootstrap.Modal(document.getElementById('editModal'));
                    modal.show();
                });
            });

            // Handle Edit Form Submission
            document.getElementById('editFlashcardForm').addEventListener('submit', (e) => {
                e.preventDefault();

                const id = document.getElementById('flashcardId').value;
                const title = document.getElementById('editTitle').value;
                const content = document.getElementById('editContent').value;

                fetch('update_flashcard.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, title, content })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to update flashcard.');
                    }
                });
            });

            // Handle Delete Button Click
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const card = e.target.closest('.card');
                    const id = card.getAttribute('data-id');

                    if (confirm('Are you sure you want to delete this flashcard?')) {
                        fetch('delete_flashcard.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id })
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                card.remove();
                            } else {
                                alert('Failed to delete flashcard.');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>