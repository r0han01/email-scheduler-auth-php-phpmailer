<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include necessary PHPMailer files
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';
include 'db_connect.php';  // Database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send scheduled emails
function sendScheduledEmails($conn) {
    // Prepare query to select emails that need to be sent
    $stmt = $conn->prepare("SELECT * FROM email_schedule WHERE status = 'pending' AND scheduled_time <= NOW()");
    $stmt->execute();
    $result = $stmt->get_result();

    $emails_sent = 0;
    $emails_failed = 0; // To track failed emails

    // Loop through each scheduled email
    while ($row = $result->fetch_assoc()) {
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rk987828@gmail.com';  // Your Gmail email
            $mail->Password = 'nqkr nzqo omkg fdtd'; // Your Gmail app password (ensure it's correct)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email content
            $mail->setFrom('rk987828@gmail.com', 'Rohan Kumar');
            $mail->addAddress($row['recipient_email']);
            $mail->isHTML(true);
            $mail->Subject = $row['subject'];
            $mail->Body = nl2br(htmlspecialchars($row['message']));

            // Send the email
            if ($mail->send()) {
                // Update email status to 'sent' after sending successfully
                $update_stmt = $conn->prepare("UPDATE email_schedule SET status = 'sent' WHERE id = ?");
                $update_stmt->bind_param("i", $row['id']);
                $update_stmt->execute();
                $update_stmt->close();
                $emails_sent++;
            } else {
                // Update email status to 'failed' if email couldn't be sent
                $update_stmt = $conn->prepare("UPDATE email_schedule SET status = 'failed' WHERE id = ?");
                $update_stmt->bind_param("i", $row['id']);
                $update_stmt->execute();
                $update_stmt->close();
                $emails_failed++;
            }
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            // Update email status to 'failed' if there is an error
            $update_stmt = $conn->prepare("UPDATE email_schedule SET status = 'failed' WHERE id = ?");
            $update_stmt->bind_param("i", $row['id']);
            $update_stmt->execute();
            $update_stmt->close();
            $emails_failed++;
        }
    }

    // Close database connection
    $stmt->close();

    // Return success or failure message
    echo json_encode([
        "message" => "$emails_sent emails sent successfully, $emails_failed emails failed."
    ]);
}

// Call the function to send scheduled emails
sendScheduledEmails($conn);

// Close database connection
$conn->close();
?>
