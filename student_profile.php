<?php
session_start();
require_once 'config.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $current_address = $_POST['current_address'];
    $permanent_address = $_POST['permanent_address'];
    $birth_date = $_POST['birth_date'];
    $nationality = $_POST['nationality'];
    $religion = $_POST['religion'];
    $biological_sex = $_POST['biological_sex'];
    $mother_name = $_POST['mother_name'];
    $mother_work = $_POST['mother_work'];
    $mother_contact = $_POST['mother_contact'];
    $father_name = $_POST['father_name'];
    $father_work = $_POST['father_work'];
    $father_contact = $_POST['father_contact'];
    $siblings_count = $_POST['siblings_count'];

    // Update query
    $update_query = "UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, email = ?, phone = ?, current_address = ?, permanent_address = ?, birth_date = ?, nationality = ?, religion = ?, biological_sex = ?, mother_name = ?, mother_work = ?, mother_contact = ?, father_name = ?, father_work = ?, father_contact = ?, siblings_count = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssssssssssssssssi", $first_name, $middle_name, $last_name, $email, $phone, $current_address, $permanent_address, $birth_date, $nationality, $religion, $biological_sex, $mother_name, $mother_work, $mother_contact, $father_name, $father_work, $father_contact, $siblings_count, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
    }

    $stmt->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <style>
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select {
            width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 5px;
        }
        button {
            width: 100%; padding: 10px; background-color: #4CAF50; color: white; font-size: 18px;
            border: none; border-radius: 5px; cursor: pointer;
        }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Update Profile</h2>
    <form method="POST" action="update_profile.php">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required />
        </div>
        <div class="form-group">
            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name" value="<?= htmlspecialchars($user['middle_name']) ?>" required />
        </div>
        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required />
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required />
        </div>
        <div class="form-group">
            <label for="biological_sex">Biological Sex:</label>
            <select id="biological_sex" name="biological_sex" required>
                <option value="Male" <?= ($user['biological_sex'] == 'Male') ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= ($user['biological_sex'] == 'Female') ? 'selected' : '' ?>>Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required />
        </div>
        <div class="form-group">
            <label for="current_address">Current Address:</label>
            <input type="text" id="current_address" name="current_address" value="<?= htmlspecialchars($user['current_address']) ?>" required />
        </div>
        <div class="form-group">
            <label for="permanent_address">Permanent Address:</label>
            <input type="text" id="permanent_address" name="permanent_address" value="<?= htmlspecialchars($user['permanent_address']) ?>" required />
        </div>
        <div class="form-group">
            <label for="birth_date">Birthdate:</label>
            <input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($user['birth_date']) ?>" required />
        </div>
        <div class="form-group">
            <label for="nationality">Nationality:</label>
            <input type="text" id="nationality" name="nationality" value="<?= htmlspecialchars($user['nationality']) ?>" required />
        </div>
        <div class="form-group">
            <label for="religion">Religion:</label>
            <input type="text" id="religion" name="religion" value="<?= htmlspecialchars($user['religion']) ?>" required />
        </div>
        <div class="form-group">
            <label for="siblings_count">Number of Siblings:</label>
            <input type="number" id="siblings_count" name="siblings_count" value="<?= htmlspecialchars($user['siblings_count']) ?>" required />
        </div>
        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>
