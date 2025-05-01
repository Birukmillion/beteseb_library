<?php
session_start();
include '../../php/db.php'; // your database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo "❌ Unauthorized Access!";
    exit();
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Collect form data
$currentPassword = $_POST['current_password'];
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];

// Validate
if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    echo "❌ Please fill all fields.";
    exit();
}

// Check if new passwords match
if ($newPassword !== $confirmPassword) {
    echo "❌ New passwords do not match.";
    exit();
}

// Fetch current password from database
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($dbPassword);
$stmt->fetch();
$stmt->close();

// Check if current password matches
if ($currentPassword !== $dbPassword) {
    echo "❌ Current password is incorrect.";
    exit();
}

// Update password
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $newPassword, $userId);

if ($stmt->execute()) {
    echo "✅ Password updated successfully!";
} else {
    echo "❌ Failed to update password.";
}

$stmt->close();
$conn->close();
?>
