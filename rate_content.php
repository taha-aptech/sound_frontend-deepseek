<!-- rate_content.php -->
<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['loggedin'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to rate content']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $content_id = intval($_POST['content_id']);
    $content_type = mysqli_real_escape_string($conn, $_POST['content_type']);
    $rating_value = intval($_POST['rating']);
    
    // Validate rating
    if ($rating_value < 1 || $rating_value > 5) {
        echo json_encode(['success' => false, 'message' => 'Invalid rating value']);
        exit();
    }
    
    // Check if user already rated this content
    $check_query = "SELECT rating_id FROM rating 
                   WHERE user_id = $user_id 
                   AND content_type = '$content_type' 
                   AND content_id = $content_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Update existing rating
        $update_query = "UPDATE rating 
                        SET rating_value = $rating_value, created_at = NOW() 
                        WHERE user_id = $user_id 
                        AND content_type = '$content_type' 
                        AND content_id = $content_id";
        $result = mysqli_query($conn, $update_query);
    } else {
        // Insert new rating
        $insert_query = "INSERT INTO rating (user_id, content_type, content_id, rating_value, created_at) 
                        VALUES ($user_id, '$content_type', $content_id, $rating_value, NOW())";
        $result = mysqli_query($conn, $insert_query);
    }
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Rating submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>