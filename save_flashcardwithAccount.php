
<?php
session_start();
header('Content-Type: application/json'); // Ensure the response is in JSON format

require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Access denied. Please log in.']);
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture data from the form (this will be sent via AJAX)
    $set_title = trim($_POST['set_title']);
    $set_description = trim($_POST['set_description']);
    $terms = $_POST['terms'] ?? [];
    $definitions = $_POST['definitions'] ?? [];

    try {
        // Start the database transaction
        $pdo->beginTransaction();

        // Insert flashcard set into the database
        $stmt = $pdo->prepare("INSERT INTO flashcard (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $set_title, $set_description]);

        // Get the ID of the inserted flashcard
        $flashcard_id = $pdo->lastInsertId();

        // Insert terms and definitions into the term_answer table
        $stmt = $pdo->prepare("INSERT INTO term_answer (flashcard_id, term, answer) VALUES (?, ?, ?)");
        for ($i = 0; $i < count($terms); $i++) {
            $term = trim($terms[$i]);
            $definition = trim($definitions[$i]);
            if (!empty($term) && !empty($definition)) {
                $stmt->execute([$flashcard_id, $term, $definition]);
            }
        }

        // Commit the transaction
        $pdo->commit();

        // Return success response
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'An unexpected error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
