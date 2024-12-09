<?php
    session_start(); 


    if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
        header('Location: loginPage.php');
        exit;
    }

    $loggedInUserId = $_SESSION['user_id']; 

    require_once 'db_config.php';

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
        <a href="landingPage.php" class="btn btn-secondary">Back</a>
        <button class="btn btn-danger position-absolute top-0 end-0 m-3" id="close-btn">Log Out</button>

        <h1 class="text-center">Your Flashcards</h1>
        <div id="flashcardList">
            <?php if (count($flashcards) > 0): ?>
                <?php foreach ($flashcards as $flashcard): ?>
                  <div class="card shadow-lg border-0 p-4" data-id="<?= $flashcard['flashcard_id'] ?>">
                        <h3 class="text-center mb-4"><?= htmlspecialchars($flashcard['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($flashcard['content'])) ?></p>
                        <small class="text-muted">Created on: <?= htmlspecialchars($flashcard['date_created']) ?></small>
                        <div class="text-center mt-3">
                            <a href="flashcardDisplay.php?flashcard_id=<?= $flashcard['flashcard_id'] ?>" class="btn btn-primary btn-sm edit-btn">Display</a>
                            <a href="editFlashcardSet.php" class="btn btn-warning btn-sm edit-btn">Edit</a>
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
    // Handle Delete Button Click
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            const card = e.target.closest('.card');
            const id = card.getAttribute('data-id');

            console.log("Deleting flashcard with ID:", id);  // Log the ID to check

            if (confirm('Are you sure you want to delete this flashcard?')) {
                // Send the delete request
                fetch('delete_flashcard.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        card.style.transition = "opacity 1s";
                        card.style.opacity = "0";
                        setTimeout(() => {
                            card.remove();
                        }, 1000);
                    } else {
                        alert('Failed to delete flashcard.');
                        console.log(data.message);  // Log error message if deletion fails
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the flashcard.');
                });
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
        const closeBtn = document.getElementById("close-btn");
        if (closeBtn) {
            closeBtn.addEventListener("click", function() {
                console.log("Close button clicked");  
                window.location.href = "FirstLandingPage.php"; 
            });
        } else {
            console.log("Close button is not functioning"); 
        }
    });

</script>

</body>
</html>