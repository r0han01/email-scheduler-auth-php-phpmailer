<?php
session_start(); // Start the session to manage user authentication

// Include database connection
include 'db_connect.php'; // Replace with your database connection file

// Change connection message
echo "<script>alert('SQL Database Connected Successfully');</script>"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Check if username is provided
    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit;
    }

    // Fetch the user from the database by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, fetch user data
        $user = $result->fetch_assoc();

        // Verify the password entered by the user against the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['username'] = $username; // Set session variable
            echo "Login successful! Welcome, " . htmlspecialchars($username);

            // Redirect to send_email.php after successful login
            header("Location: send_email.php");
            exit; // Stop further script execution
        } else {
            // Incorrect password
            echo "Incorrect password. Please try again.";
        }
    } else {
        // User does not exist
        echo "Username does not exist. Please register first.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!-- HTML Form for login -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            padding: 0 10px;
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
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
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
            width: 100%;
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
        <h2>Login</h2>

        <form action="login.php" method="POST" class="form">
            <div class="form__field">
                <input type="text" id="username" name="username" class="form__input" placeholder=" " required>
                <label for="username">Username</label>
            </div>

            <div class="form__field">
                <input type="password" id="password" name="password" class="form__input" placeholder=" " required>
                <label for="password">Password</label>
            </div>

            <div class="form__field">
                <button type="submit" class="form__submit-button">Login</button>
            </div>
        </form>

        <p class="text-center">Need an account? <a href="register.php">Sign up now</a></p>
    </div>

</body>
</html>
