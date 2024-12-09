<?php
// delete_flashcard.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

require_once 'db_config.php';

// Get the flashcard ID from the request
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'No flashcard ID provided']);
    exit;
}

$flashcardId = $data['id'];
$userId = $_SESSION['user_id'];

// Prepare SQL query to delete the flashcard
$query = "DELETE FROM flashcard WHERE flashcard_id = :flashcard_id AND user_id = :user_id";
$stmt = $pdo->prepare($query);

// Bind parameters
$stmt->bindParam(':flashcard_id', $flashcardId, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

// Attempt to execute the query
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    // Log the error to PHP error log
    error_log("Failed to delete flashcard with ID: $flashcardId for user ID: $userId");
    echo json_encode(['success' => false, 'message' => 'Failed to delete flashcard']);
}
?>
