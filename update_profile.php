<?php
session_start();
require_once "config.php"; // Database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired. Please log in again.']);
        exit;
    }

    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Get form data and sanitize
    $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
    $middle_name = isset($_POST['middle_name']) ? htmlspecialchars(strip_tags($_POST['middle_name'])) : NULL; // ✅ Middle Name (nullable)
    $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
    $birth_date = htmlspecialchars(strip_tags($_POST['birth_date']));
    $nationality = htmlspecialchars(strip_tags($_POST['nationality']));
    $religion = htmlspecialchars(strip_tags($_POST['religion']));
    $biological_sex = htmlspecialchars(strip_tags($_POST['biological_sex']));
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $phone = htmlspecialchars(strip_tags($_POST['phone']));
    $current_address = htmlspecialchars(strip_tags($_POST['current_address']));
    $permanent_address = htmlspecialchars(strip_tags($_POST['permanent_address']));
    $mother_name = htmlspecialchars(strip_tags($_POST['mother_name']));
    $mother_work = htmlspecialchars(strip_tags($_POST['mother_work']));
    $mother_contact = htmlspecialchars(strip_tags($_POST['mother_contact']));
    $father_name = htmlspecialchars(strip_tags($_POST['father_name']));
    $father_work = htmlspecialchars(strip_tags($_POST['father_work']));
    $father_contact = htmlspecialchars(strip_tags($_POST['father_contact']));
    $siblings_count = intval($_POST['siblings_count']); // ✅ Integer type
    $unit = isset($_POST['unit']) ? htmlspecialchars(strip_tags($_POST['unit'])) : NULL; // ✅ Nullable field

    // Validate phone number (should be 11 digits)
    if (!preg_match("/^[0-9]{11}$/", $phone)) {
        echo json_encode(['status' => 'error', 'message' => 'Phone number should be 11 digits.']);
        exit;
    }

    // Ensure database connection is working
    if (!$conn) {
        die(json_encode(['status' => 'error', 'message' => 'Database connection issue.']));
    }

    // Update the profile in the database
    $query = "UPDATE users SET 
        first_name = ?, middle_name = ?, last_name = ?, birth_date = ?, nationality = ?, religion = ?, 
        biological_sex = ?, email = ?, phone = ?, current_address = ?, permanent_address = ?, 
        mother_name = ?, mother_work = ?, mother_contact = ?, father_name = ?, father_work = ?, 
        father_contact = ?, siblings_count = ?, unit = ? 
        WHERE user_id = ?";
        
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'SQL Error: ' . $conn->error]);
        exit;
    }

    // Bind parameters and execute the query
    $stmt->bind_param("ssssssssssssssssissi", 
        $first_name, $middle_name, $last_name, $birth_date, $nationality, $religion, 
        $biological_sex, $email, $phone, $current_address, $permanent_address, 
        $mother_name, $mother_work, $mother_contact, $father_name, $father_work, 
        $father_contact, $siblings_count, $unit, $user_id
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile.', 'error' => $stmt->error]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
