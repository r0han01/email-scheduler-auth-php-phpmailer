<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include PHPMailer files from the src folder
require_once 'src/PHPMailer.php';  // Path to PHPMailer.php
require_once 'src/SMTP.php';       // Path to SMTP.php
require_once 'src/Exception.php';  // Path to Exception.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHmailer\Exception;

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $recipientEmail = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $file = $_FILES['attachment']; // Get the uploaded file

    // Validate inputs
    if (empty($recipientEmail) || empty($subject) || empty($message)) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'All fields are required!'];
        header("Location: send_email.php");
        exit;
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rk987828@gmail.com'; // Your Gmail email
        $mail->Password   = 'nqkr nzqo omkg fdtd'; // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender and recipient details
        $mail->setFrom('rk987828@gmail.com', 'Your Name'); // Sender's email and name
        $mail->addAddress($recipientEmail); // Recipient's email

        // Check if a file is uploaded
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Validate file type (optional, example for PDF only)
            $allowedTypes = ['application/pdf'];
            if (!in_array($file['type'], $allowedTypes)) {
                $_SESSION['alert'] = ['type' => 'error', 'message' => 'Only PDF files are allowed!'];
                header("Location: send_email.php");
                exit;
            }

            // Attach the file
            $mail->addAttachment($file['tmp_name'], $file['name']); // Add attachment
        }

        // Content settings
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = nl2br(htmlspecialchars($message)); // Convert newlines to <br> and sanitize the message

        // Send the email
        if ($mail->send()) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Email has been sent successfully!'];
        } else {
            $_SESSION['alert'] = ['type' => 'error', 'message' => 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo];
        }
    } catch (Exception $e) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo];
    }

    // Redirect back to the form page with the message
    header("Location: send_email.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('https://images.unsplash.com/photo-1477346611705-65d1883cee1e?dpr=0.800000011920929&auto=format&fit=crop&w=1199&h=800&q=80&cs=tinysrgb&crop=');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Montserrat', sans-serif;
            color: #fff;
            padding: 0 10px; /* Ensure padding is on all sides */
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%; /* Take the full width */
            max-width: 1000px; /* Set a maximum width for large screens */
            margin: 0 auto; /* Center the form container */
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 700;
        }

        .form {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .form__field {
            position: relative;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        label {
            font-size: 14px;
            color: #fff;
            position: absolute;
            top: 10px;
            left: 10px;
            pointer-events: none;
            transition: 0.3s;
        }

        .form__input {
            width: 100%;
            padding: 1rem;
            background-color: rgba(255, 255, 255, 0.3);
            color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: background-color 0.3s;
            backdrop-filter: blur(10px);
            font-weight: bold;
        }

        .form__input:focus {
            background-color: rgba(255, 255, 255, 0.4);
            outline: none;
        }

        .form__input:focus + label,
        .form__input:not(:placeholder-shown) + label {
            top: -10px;
            font-size: 12px;
        }

        .form__submit-button {
            padding: 18px 30px;
            font-weight: 700;
            background: linear-gradient(135deg, rgba(234, 76, 136, 1) 0%, rgba(212, 65, 121, 1) 100%);
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            text-transform: uppercase;
            font-size: 14px;
            width: 100%; /* Full width button */
        }

        .form__submit-button:hover {
            background: linear-gradient(135deg, rgba(212, 65, 121, 1) 0%, rgba(234, 76, 136, 1) 100%);
            transform: scale(1.01);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .form__submit-button:active {
            transform: scale(1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .alert {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }

        .alert.success {
            background-color: #28a745;
            color: white;
        }

        .alert.error {
            background-color: #dc3545;
            color: white;
        }

        .attachment-info {
            font-size: 12px;
            color: #ccc;
            margin-top: 5px;
        }

        .form__input[type="file"] {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .form__input[type="file"]:hover {
            border-color: #ccc;
        }

        .text-center {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #eee;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Send Email Using .php</h2>

        <!-- Alert message -->
        <?php if (isset($_SESSION['alert'])): ?>
            <div class="alert <?php echo $_SESSION['alert']['type']; ?>">
                <?php echo $_SESSION['alert']['message']; ?>
            </div>
            <?php unset($_SESSION['alert']); // Clear the message after displaying it ?>
        <?php endif; ?>

        <form action="send_email.php" method="POST" enctype="multipart/form-data" class="form">
            <div class="form__field">
                <input type="email" id="email" name="email" class="form__input" placeholder=" " required>
                <label for="email">Recipient Email</label>
            </div>

            <div class="form__field">
                <input type="text" id="subject" name="subject" class="form__input" placeholder=" " required>
                <label for="subject">Subject</label>
            </div>

            <div class="form__field">
                <textarea id="message" name="message" class="form__input" rows="4" placeholder=" " required></textarea>
                <label for="message">Message</label>
            </div>

            <div class="form__field">
                <input type="file" id="attachment" name="attachment" accept=".pdf" class="form__input">
                <p class="attachment-info">Only PDF files are allowed.</p>
            </div>

            <div class="form__field">
                <button type="submit" class="form__submit-button">Send Email</button>
            </div>
        </form>

        <p class="text-center">Need an account? <a href="register.php">Sign up now</a></p>
    </div>

</body>
</html>