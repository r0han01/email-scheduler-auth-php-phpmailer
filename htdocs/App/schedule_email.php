<?php
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';
include 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json'); // Set JSON response type

    // Get form inputs
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $scheduled_time = $_POST['schedule_time']; // Get the scheduled time from the form

    // Validate inputs
    if (empty($email) || empty($subject) || empty($message) || empty($scheduled_time)) {
        echo json_encode(["status" => "error", "message" => "All fields are required!"]);
        exit;
    }

    // Convert the scheduled time to MySQL datetime format
    $scheduled_time = str_replace('T', ' ', $scheduled_time) . ':00'; // Add seconds part (00)

    // Insert the email schedule into the database
    $stmt = $conn->prepare("INSERT INTO email_schedule (recipient_email, subject, message, scheduled_time) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ssss", $email, $subject, $message, $scheduled_time);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Email successfully scheduled for " . $scheduled_time]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to schedule email. Please try again."]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
