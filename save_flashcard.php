<?php

// Ensure that the response is in JSON format
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db_config.php'; 

session_start();

// Log the raw POST data for debugging
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// Log the decoded data
file_put_contents('debug_log.txt', print_r($data, true), FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $user_id = $data['flashcardData']['user_id'] ?? null;
    $set_title = $data['flashcardData']['set_title'] ?? null;
    $set_description = $data['flashcardData']['set_description'] ?? null;
    $flashcards = $data['flashcardData']['flashcards'] ?? [];

    if (!$user_id || !$set_title || !$set_description || empty($flashcards)) {
        die(json_encode(['status' => 'error', 'message' => 'Invalid input data']));
    }

    try {
        $pdo->beginTransaction();

        // Insert flashcard set
        $stmt = $pdo->prepare("INSERT INTO flashcard (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $set_title, $set_description]);
        $flashcard_id = $pdo->lastInsertId();

        // Insert flashcard terms and definitions
        $termStmt = $pdo->prepare("INSERT INTO term_answer (flashcard_id, term, answer) VALUES (?, ?, ?)");
        foreach ($flashcards as $card) {
            $termStmt->execute([$flashcard_id, $card['term'], $card['definition']]);
        }

        $pdo->commit();

        // Respond with success
        echo json_encode(['status' => 'success', 'message' => 'Flashcard set saved successfully']);
    } catch (Exception $e) {
        $pdo->rollBack();
        // Log the error in case of a rollback
        file_put_contents('debug_log.txt', 'Error: ' . $e->getMessage(), FILE_APPEND);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    die(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
}
?>
