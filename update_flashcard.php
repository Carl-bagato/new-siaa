<?php
// update_flashcard.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

require_once 'db_config.php';

// Get the data sent in the request
$data = json_decode(file_get_contents('php://input'), true);

// Check if the required data is provided
if (!isset($data['id']) || !isset($data['title']) || !isset($data['content'])) {
    echo json_encode(['success' => false, 'message' => 'Missing flashcard data']);
    exit;
}

$flashcardId = $data['id'];
$title = $data['title'];
$content = $data['content'];
$userId = $_SESSION['user_id'];

// Prepare the SQL query to update the flashcard
$query = "UPDATE flashcard SET title = :title, content = :content WHERE flashcard_id = :flashcard_id AND user_id = :user_id";
$stmt = $pdo->prepare($query);

// Bind parameters
$stmt->bindParam(':title', $title, PDO::PARAM_STR);
$stmt->bindParam(':content', $content, PDO::PARAM_STR);
$stmt->bindParam(':flashcard_id', $flashcardId, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update flashcard']);
}
?>
